{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
*
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<script type="text/javascript">
    pcStartsWith = function(str, search) {
        return typeof str === 'string' && str.substr(0, search.length) === search;
    };
    processDynamicModules = function(dyndatas) {
        for (var key in dyndatas) {
            if (key=='js') {
                // Keep spaces arround 'key', some Prestashop removes [key] otherwise (?!)
                $('body').append(dyndatas[ key ]);
            }
            else if (pcStartsWith(key, 'dyn')) {
                // Keep spaces arround 'key', some Prestashop removes [key] otherwise (?!)
                try {
                    $('#'+key).replaceWith(dyndatas[ key ]);
                }
                catch (error) {
                    console.error('A javasript error occured during the "eval" of the refreshed content ' + key + ': ' + error);
                }
            }
        }
        if (typeof pcRunDynamicModulesJs == 'function') {
            pcRunDynamicModulesJs();
        }
    };
</script>
