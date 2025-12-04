{**
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
*}

{if !empty($motive_x_url)}
  <link id="motive-layer-js" rel="preload" href="{$motive_x_url}" as="script" crossorigin="anonymous">
{/if}
{if !empty($motive_front)}
  <script type="text/javascript">const motive = {$motive_config nofilter};</script>
  <script type="text/javascript" src="{$motive_front}"></script>
{/if}
{if !empty($interoperability_js)}
  <script type="text/javascript" src="{$interoperability_js}"></script>
{/if}
