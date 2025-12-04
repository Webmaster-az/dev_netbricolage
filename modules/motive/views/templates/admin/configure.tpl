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

<mot-configuration-page id="mot-configuration-page"
  locale="{$locale}"
  token="{$token}"
  platform="PrestaShop"
  version="{$version}"
  is-configured="{$isConfigured}"
  is-enabled="{$isEnabled}"
>
</mot-configuration-page>
<script>
  document.getElementById('mot-configuration-page').onRegenerateToken = function () {
		return fetch(motive_configUrl + '&regenerate-token=1', {
			method: 'GET'
		}).then(res => res.text());
	}
</script>
<script type="module" src="https://assets.motive.co/configuration-page/configuration-page.js"></script>
