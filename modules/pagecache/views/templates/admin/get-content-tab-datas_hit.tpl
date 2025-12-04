{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
*
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<span style="color:green">{$count_hit|intval}</span>&nbsp;/&nbsp;<span style="color:red">{$count_missed|intval}</span>
({math equation="round(count_hit * 100 / max(1, (count_hit + count_missed)),1)" count_hit="$count_hit" count_missed="$count_missed"}%)