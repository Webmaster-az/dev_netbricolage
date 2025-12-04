<?php
/**
 * (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 *
 * This file is part of Motive Commerce Search.
 *
 * This file is licensed to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author Motive (motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Motive\Prestashop;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class TimeLimit
 * Helps to track script duration to avoid timeouts and max execution time limits
 */
class TimeLimit
{
    protected $cpuTimeLimit;
    protected $debugMode;
    protected $realTimeLimit;
    protected $useRealTime;
    protected $cpuIgnoredTime = 0;

    /**
     * TimeLimit constructor.
     *
     * @param int|null $cpuTimeLimit in seconds
     * @param int $realTimeLimit in seconds
     */
    public function __construct($cpuTimeLimit = null, $realTimeLimit = 300, $debugMode = false)
    {
        $this->cpuTimeLimit = (int) ini_get('max_execution_time');
        $this->realTimeLimit = $realTimeLimit;
        $this->debugMode = $debugMode;
        $phpOs = PHP_OS;
        $isWindows = $phpOs[0] === 'W';
        $rusageAvailable = function_exists('getrusage');

        // If getrusage is not available, or on Windows before PHP 7.0: The real time is measured.
        $this->useRealTime = !$rusageAvailable || ($isWindows && PHP_MAJOR_VERSION < 7);

        if ($cpuTimeLimit !== null) {
            $this->setCpuTimeLimit($cpuTimeLimit);
        }
    }

    /**
     * @return int
     */
    public function getRealTimeLimit()
    {
        return $this->realTimeLimit;
    }

    /**
     * @param int $realTimeLimit
     */
    public function setRealTimeLimit($realTimeLimit)
    {
        $this->realTimeLimit = $realTimeLimit;
    }

    /**
     * @return int
     */
    public function getCpuTimeLimit()
    {
        return $this->cpuTimeLimit;
    }

    /**
     * Set CPU time limit in seconds since now.
     *
     * @param int $seconds
     */
    public function setCpuTimeLimit($seconds)
    {
        if ($seconds < 0) {
            $seconds = 0;
        }

        $this->cpuIgnoredTime += $this->elapsedCpuTime();

        if (function_exists('set_time_limit')) {
            @set_time_limit($seconds);
        } elseif (function_exists('ini_set')) {
            @ini_set('max_execution_time', $seconds);
        }

        $this->cpuTimeLimit = (int) ini_get('max_execution_time');
    }

    /**
     * @return int remaining CPU time in seconds
     *
     * @throws TimeLimitChangedException
     */
    public function remainingTime()
    {
        return min($this->remainingCpuTime(), $this->remainingRealTime());
    }

    /**
     * @return int remaining CPU time in seconds
     *
     * @throws TimeLimitChangedException
     */
    public function remainingCpuTime()
    {
        if ($this->debugMode && (int) ini_get('max_execution_time') !== $this->cpuTimeLimit) {
            throw new TimeLimitChangedException('CPU time limit changed without using this class.', 1);
        }

        // No CPU time limitation
        if ($this->cpuTimeLimit === 0) {
            return (float) PHP_INT_MAX;
        }

        return $this->cpuTimeLimit - $this->elapsedCpuTime();
    }

    /**
     * @return float elapsed CPU time in seconds
     */
    public function elapsedCpuTime()
    {
        if ($this->useRealTime) {
            return (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) - $this->cpuIgnoredTime;
        }

        // Any time spent on activity that happens outside the execution of the script is not included.
        // @see http://php.net/manual/en/function.set-time-limit.php
        $usages = getrusage();

        return $usages['ru_stime.tv_sec'] + $usages['ru_utime.tv_usec'] / 1000000 - $this->cpuIgnoredTime;
    }

    /**
     * @return float remaining real time in seconds
     */
    public function remainingRealTime()
    {
        return $this->realTimeLimit - $this->elapsedRealTime();
    }

    /**
     * @return float elapsed Real time in seconds
     */
    public function elapsedRealTime()
    {
        return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
    }
}
