<div style="padding: 0px;" class="card col-md-12 printnotdisplay">
    <div class="card-header">
        <h3 class="card-header-title">
            {l s='Products shipped' mod='ec_reliquat'}
        </h3>
    </div>
     <table id="reliquat_table" class="table">
        <thead>
            <tr class="nodrag nodrop">
                <th class="text-center">
                    <span class="title_box"></span>
                </th>
                <th class="text-center">
                    <span class="title_box">DATA</span>
                </th>
                <th class="text-center">
                    <span class="title_box">COMP</span>
                </th>
                <th class="text-center">
                    <span class="title_box">LAR</span>
                </th>
                <th class="text-center">
                    <span class="title_box">ALT</span>
                </th>
                <th class="text-center">
                    <span class="title_box">PESO</span>
                </th>
                <th class="text-center">
                    <span class="title_box">Nº Caixas</span>
                </th>
                <th class="text-center">
                    <span>{l s='Tracking Number' mod='ec_reliquat'}</span>
                </th>
                <!--
                <th class="text-center"><span class="title_box">{l s='Carrier' mod='ec_reliquat'}</span></th>
                <th class="text-center"><span class="title_box">{l s='Current state' mod='ec_reliquat'}</span></th>
                -->
                <th class="text-center">{l s='Delivery slip' mod='ec_reliquat'}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <script>
                var num_packages_f = 0;
            </script>
            {foreach $reliquats as $reliquat}
                <script>
                    num_packages_f++;
                </script>
                <span style="display:none;">{$num_packages_f++}</span>
                <tr>
                    <td class="text-center">
                        <span class="deletereliquatproduct" data-reliquat="{$reliquat.id_reliquat}" style="cursor: pointer;color: #00aff0;user-select: none;">
                            <i class="material-icons" style="font-size: 25px;">delete</i>
                        </span>
                    </td>
                    <td class="text-center">
                        {dateFormat date=$reliquat.date_add full=0}
                    </td>
                    <td class="text-center td-reliquat">
                        {$reliquat.comprimento}cm
                    </td>
                    <td class="text-center td-reliquat">
                        {$reliquat.largura}cm
                    </td>
                    <td class="text-center td-reliquat">
                        {$reliquat.altura}cm
                    </td>
                    <td class="text-center td-reliquat">
                        {$reliquat.peso}kg
                    </td>
                    <td class="text-center td-reliquat">
                        {$num_packages_f}/<span id="num_packages">{$reliquats|@count}</span>
                    </td>
                    <td class="text-center td-reliquat">
                        <a target="_blank" href="https://parcelsapp.com/fr/tracking/{$reliquat.tracking_number}">{$reliquat.tracking_number}</a>
                    </td>
                    <!--
                    <td class="text-center td-reliquat">{$reliquat.carrier}</td>
                    <td class="text-center td-reliquat">{$reliquat.order_state}</td>
                    -->
                    <td class="text-center td-reliquat"><a title="{$reliquat.date_add} " href="{$link_delivery_slip}&id_order={$reliquat.id_order}&id_reliquat={$reliquat.id_reliquat}"><i class="material-icons">cloud_download</i></a></td>
                    <td style="text-align: right;">
                        <button style="margin-right:10px;" name="products" class="btn btn-default btn-sm" type="button" onclick="ShowProducts({$reliquat.id_reliquat}); return false;">{l s='Products' mod='ec_reliquat'}
                        {if $reliquat['attachments']}
                            <button style="margin-right:10px;" name="products" class="btn btn-default btn-sm" type="button" onclick="ShowAttachments({$reliquat.id_reliquat}); return false;">{l s='Attachments' mod='ec_reliquat'}</button>
                        {/if}
                        <button type="button" data-info_reliquat='{$reliquat|json_encode}' class="editreliquat btn btn-info btn-sm" data-toggle="modal" data-target="#editreliquat">{l s='Edit' mod='ec_reliquat'}</button>
                    </td>
                </tr>
                <tr style="width: 100%; display: none;" id="products{$reliquat.id_reliquat}">
                    <td colspan="10" style="width:100%">
                        <table style="width: 100%;" class="table" >
                            <thead>
                                <tr>
                                    <th style="width:10%;">&nbsp;</th>
                                    <th style="width:50%;"><span class="title_box ">{l s='Product' mod='ec_reliquat'}</span></th>
                                    <th style="width:10%;"><span class="title_box ">{l s='Quantity' mod='ec_reliquat'}</span></th>
                                    <th style="width:5%;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $reliquat['products'] as $product}
                                    <tr class="product-line-row" height="52">
                                        <td><img src="{$ec_base_uri}/img/tmp/product_mini_{$product['product_id']}_{$product['product_attribute_id']}.jpg?time=1554738725" alt="" class="imgm img-thumbnail"></td>
                                        <td  style="color:#00aff0;">
                                            <span class="productName">{$product['product_name']}</span><br>
                                            {$product['product_reference']}<br>                                                            
                                        </td>
                                        <td class="productQuantity">
                                            <span class="product_quantity_show red bold">{$product['quantity']}</span>
                                        </td>
                                        <td>
                                            <span class="deleteproduct" data-reliquat-product="{$product['id_reliquat_product']}" style="cursor: pointer;/*color: #00aff0*/;user-select: none;">
                                                <i class="material-icons" style="font-size: 25px;">delete</i>
                                            </span>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr style="display: none;" id="attachments{$reliquat.id_reliquat}">
                    <td colspan="8" style="width:100%">
                        <table style="width: 100%;" class="table" >
                            <thead>
                                <tr>
                                    <th style="width:30%;"><span class="title_box ">{l s='Filename' mod='ec_reliquat'}</span></th>
                                    <th style="width:10%;"><span class="title_box ">{l s='Extension' mod='ec_reliquat'}</span></th>
                                    <th><span class="title_box ">{l s='Type' mod='ec_reliquat'}</span></th>
                                    <th><span class="title_box ">{l s='Add date' mod='ec_reliquat'}</span></th>
                                    <th><span class="title_box ">{l s='Download date' mod='ec_reliquat'}</span></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $reliquat['attachments'] as $attachement}
                                    <tr class="product-line-row" height="52">
                                        <td>{$attachement['name']}</td>
                                        <td>
                                            <span class="productName">{$attachement['extension']}</span>                                                        
                                        </td>
                                        <td class="productQuantity">
                                            <span class="product_quantity_show red bold">{$attachement['type']}</span>
                                        </td>
                                        <td class="productQuantity product_stock">{$attachement['date_add']}</td>                        
                                        <td class="productQuantity product_stock">{$attachement['date_download']}</td>
                                        <td class="productQuantity product_stock"><a title="{l s='Donwload' mod='ec_reliquat'}" href="{$dl_script}&k={$attachement['cle']}"><i class="material-icons">cloud_download</i></a><a class="deleteattachment" data-cle="{$attachement['cle']}" title="{l s='Delete' mod='ec_reliquat'}" href="#"><i class="material-icons">delete</i></a></td>  
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <div id="editreliquat" class="bootstrap modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{l s='Edit Reliquat' mod='ec_reliquat'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Comprimento</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_comprimento form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Largura</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_largura form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Altura</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_altura form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Peso</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_peso form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">{l s='Traking number' mod='ec_reliquat'}</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_trackingnumber form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12"> {l s='Carrier' mod='ec_reliquat'}</label>
                        <div class="col-lg-12 input-group">
                            <select class="ec_carrier form-control" class="selectpicker">
                                {foreach from=$carriers item=carrier}
                                    <option value="{$carrier['id_carrier']|escape:'htmlall':'UTF-8'}">{$carrier['name']|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12"> {l s='Order states' mod='ec_reliquat'}</label>
                        <div class="col-lg-12 input-group">
                            <select class="ec_order_state form-control" class="selectpicker">
                                {foreach from=$order_states item=order_state}
                                    <option value="{$order_state['id_order_state']|escape:'htmlall':'UTF-8'}">{$order_state['name']|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closereliquat" type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='ec_reliquat'}</button>
                <button id="btneditreliquat" class="btn btn-primary">{l s='Edit' mod='ec_reliquat'}</button>
            </div>
        </div>
    </div>
</div>
    <div class="col-md-12 form_buttons" style="background-color: white;text-align: right;padding: 10px;">
        <button name="products" class="btn btn-default" type="button" data-toggle="modal" data-target="#etiqueta_modal">
            Editar etiqueta
        </button>
        <button id="submitform" type="button" value="Guardar" class="btn btn-primary ml-3">
            Imprimir Etiqueta
        </button>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="etiqueta_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding:0px;">
                <form style="width:auto;" class="col-12" style="float:left;" id="formulario" method="GET">			
                    <fieldset>
                        <div class="col-md-6" style="float: left;">
                            <label for="Transportadora">Transportadora:</label>
                            <input class="form-control" name="transportadora" id="transportadora" value="{foreach $reliquats as $reliquat}{if $reliquat@iteration == 1}{$reliquat.carrier}{/if}{/foreach}" />                                
                        </div>
                        <div class="col-md-6" style="float: left;">
                            <label>Cliente:</label>
                            <input class="cliente form-control" type="text" id="cliente" placeholder="Cliente" value="{$addresses.delivery->firstname} {$addresses.delivery->lastname}">
                        </div>
                        <div class="col-md-12" style="float: left;">
                            <label>Endereço:</label>
                            <input class="endereço form-control" type="text" id="endereço" placeholder="Endereço" value="{$addresses.delivery->address1} {$addresses.delivery->address2}">
                        </div>
                        <div class="col-md-6" style="display: flex; float:left;">
                            <div class="col-md-6" style="padding-left: 0px;">
                                <label>Cod.Postal:</label>
                                <input class="cod.postal form-control" type="text" id="codpostal" placeholder="Cod.Postal" value="{$addresses.delivery->postcode}">
                            </div>
                            <div class="col-md-6" style="padding-right: 0px;">
                                <label>Cidade:</label>
                                <input class="cidade form-control" type="text" id="cidade" placeholder="Cidade" value="{$addresses.delivery->city}">
                            </div>
                        </div>
                        <div class="col-md-6" style="float: left;">
                            <label for="pais">País:</label>
                            <input class="form-control" name="pais" id="pais" />
                        </div>
                        <div class="col-md-6" style="float: left;">
                            <label>Tel :</label>
                            <input class="telemovel form-control" type="text" id="telemovel" placeholder="Nº telemovel" value="{if $addresses.delivery->phone_mobile}{$addresses.delivery->phone_mobile}{else}{$addresses.delivery->phone}{/if}">
                        </div>
                        <div class="col-md-6" style="float: left;">
                            <label>Email:</label>
                            <input class="email form-control" type="email" id="email" placeholder="E-mail" value="{$customer->email}">
                        </div>
                        <div class="col-md-12" style="float: left;">
                            <label for="msg">Observações:</label>
                            <textarea class="form-control" id="msg" placeholder="Mensagem"></textarea>
                        </div>
                        <div class="col-md-12">                            
                            <div class="col-md-6">
                                <label>Nº Caixas:</label>
                                <div style="width:100%;">
                                    <span id="vol_me1" onclick="remove_vol()">-</span>
                                    <input class="volumes" id="volumes" min="1" value="1" disabled>
                                    <span id="vol_ma1" onclick="add_new_vol()">+</span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <div style="width:auto;" id="num_vols" style="float: left;margin-left: 10px;">
                    {foreach $reliquats as $reliquat}                        
                        <form style="float: left;border-top: 1px solid #bbcdd2;padding-top: 20px;margin-top: 10px;" id="block_voltocopy" class="clone col-md-12" action="">
                            <div class="col-md-3" style="float:left;">
                                <img style="width: 100%;" src="/img/package_icon.png" />
                            </div>
                            <div style="float: left;padding-left:0px;" class="col-md-4">
                                <label>Comprimento:</label>
                                <input class="comprimento form-control" type="text" id="comprimento" placeholder="Comprimento" value="{$reliquat.comprimento}">
                            </div>
                            <div style="float: left;padding-right:0px;" class="col-md-4">
                                <label>Largura:</label>
                                <input class="largura form-control" type="text" id="largura" placeholder="Largura" value="{$reliquat.largura}">
                            </div>
                            <div style="float: left;padding-left:0px;" class="col-md-4">
                                <label>Altura:</label>
                                <input class="altura form-control" type="text" id="altura" placeholder="Altura" value="{$reliquat.altura}">
                            </div>
                            <div style="float: left; padding-right:0px;" class="col-md-4">
                                <label>Peso Total:</label>
                                <input class="peso form-control" type="text" id="peso" placeholder="Peso Total" value="{$reliquat.peso}">
                            </div>
                        </form>
                    {/foreach}
                </div>
                <div style="display: none;" class="clone">
                    <form id="block_voltocopy" class="clone col-md-12" action="">				
                        <div class="col-md-6">
                            <label>Comprimento:</label><input class="comprimento" type="text" id="comprimento" placeholder="Comprimento">
                        </div>
                        <div class="col-md-6">
                            <label>Largura:</label><input class="largura" type="text" id="largura" placeholder="Largura">
                        </div>
                        <div class="col-md-6">
                            <label>Altura:</label><input class="altura" type="text" id="altura" placeholder="Altura">
                        </div>
                        <div class="col-md-6">
                            <label>Peso Total:</label><input class="peso" type="text" id="peso" placeholder="Peso Total">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button id="submitformsec" type="button" value="Guardar" class="btn btn-primary">Imprimir</button>
            </div>
        </div>
    </div>
</div>
<div id="editreliquat" class="bootstrap modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{l s='Edit Reliquat' mod='ec_reliquat'}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="panel-body">
                    {* <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Comprimento</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_comprimento form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Largura</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_largura form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Altura</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_altura form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">Peso</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_peso form-control" type="text"/>
                        </div>
                    </div> *}
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12">{l s='Traking number' mod='ec_reliquat'}</label>
                        <div class="col-lg-12 input-group">
                            <input class="ec_trackingnumber form-control" type="text"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12"> {l s='Carrier' mod='ec_reliquat'}</label>
                        <div class="col-lg-12 input-group">
                            <select class="ec_carrier form-control" class="selectpicker">
                                {foreach from=$carriers item=carrier}
                                    <option value="{$carrier['id_carrier']|escape:'htmlall':'UTF-8'}">{$carrier['name']|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-1"></div>
                        <label class="col-lg-12"> {l s='Order states' mod='ec_reliquat'}</label>
                        <div class="col-lg-12 input-group">
                            <select class="ec_order_state form-control" class="selectpicker">
                                {foreach from=$order_states item=order_state}
                                    <option value="{$order_state['id_order_state']|escape:'htmlall':'UTF-8'}">{$order_state['name']|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="closereliquat" type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='ec_reliquat'}</button>
                <button id="btneditreliquat" class="btn btn-primary">{l s='Edit' mod='ec_reliquat'}</button>
            </div>
        </div>
    </div>
</div>
    <div>        
        <link rel="stylesheet" media="screen" href="/modules/ec_reliquat/views/css/style.css">
        <link rel="stylesheet" media="print" href="/modules/ec_reliquat/views/css/print.css" >
        {assign var=num_packages value=1}
        <script>
            var num_packages = 0;
        </script>
        {foreach $reliquats as $reliquat}
            <script>
                num_packages++;
            </script>
            <span style="display:none;">{$num_packages++}</span>
        {/foreach}
        <div id="area">
            <div class="print">
            </div>
            <div style="display:none;" class="content_to_print">
                <div id="page">
                    <div class="transportadora">
                        <span class="a">Shipping Company : </span>
                        <span class="transportadora_result"></span> <!--Transportadora-->
                    </div>
                    <div class="header">
                        <div class="header-dados">
                            <span class="result1" style="font-weight: bold;text-transform: uppercase;"></span><br><br> <!--Nome-->
                            <span class="result2"></span><br> <!--Rua-->
                            <span class="result3"></span> <!--Cod.Postal-->
                            <span class="result4"></span><br><!--Cidade-->
                            <span class="result5"></span><br><br> <!--País-->
                            <span class="result7"></span><br> <!--tel-->
                            <span class="result8" class="email"></span><br> <!--email-->
                        </div>
                        <p class="c">Packages :</p>
                        <div class="header-volume">
                        <span class="resultv"></span><br><!--Volumes-->
                        </div>
                    </div>
                    <div class="body" style="float:left;">
                        <span class="result">NB-{$ec_id_order}</span>
                    </div>
                    <div class="body-dimensoes">
                        <div class="body-dimensoes-text">
                            <div>
                                <p>Lenght <br> (cm)</p>
                                <span class="result12"> </span>
                            </div>
                            <div>
                                <p>Width <br> (cm)</p>
                                <span class="result13"> </span> 
                            </div>
                            <div>
                                <p>Height <br> (cm)</p>
                                <span class="result14"> </span> 
                            </div>
                            <div>
                                <p>Weight <br> (kg)</p>
                                <span class="result9"> </span> 
                            </div>
                        </div>
                    </div>
                    <span class="b">Comments : </span>
                    <br>
                    <div class="obs">				
                        <span class="result11"></span>
                    </div>
                    <div class="footer">   
                        <span style="font-size:11px;">Dispatcher : </span>
                        <div style="display:flex;align-items: center;">
                            <div class="footer-img">            
                                <img style="padding:10px;" src="/modules/ec_reliquat/views/img/azpreto.png" />
                            </div>
                            <div class="footer-txt">
                                <p>Tel : +351 252 311 693</p>
                                <p>geral@azhabitaçao.com</p>
                            </div> 
                        </div>
                        <div class="footer-footer">
                            <span>AVENIDA JORGE REIS, 1484 </span> <br>
                            <span>4760-692 OUTIZ PORTUGAL</span>
                        </div>
                    </div>            
                </div> <!-- END PAGE -->
            </div>
            <script>
                function func_copy_inputs() {
                    $("#num_vols #block_voltocopy").each(function(i) {
                        $(this).addClass("page_" + (i + 1));
                    });
                    $(".print #page").each(function(i) {
                        var num = i+1;
                        $(this).addClass("page_" + num);
                        var valtoget = $("#num_vols .page_"+num+" #comprimento").val();
                        var valtoget1 = $("#num_vols .page_"+num+" #largura").val();
                        var valtoget2 = $("#num_vols .page_"+num+" #altura").val();
                        var valtoget3 = $("#num_vols .page_"+num+" #peso").val();
                        $(this).find(".result12").text(valtoget);
                        $(this).find(".result13").text(valtoget1);
                        $(this).find(".result14").text(valtoget2);
                        $(this).find(".result9").text(valtoget3);
                    });   
                }
                $(".botao").click(function(){
                    $(".botao1").show();
                });
                $(".botao1").click(function(){
                    $(".botao1").hide();
                });
                function do_volume_num() {
                    $(".print").empty();                   
                    for (let i = 0; i < num_packages; i++) {                  
                        $("#page").clone().appendTo(".print").find(".resultv").html(i+1 + "/" + num_packages);
                    }
                }
                function add_new_vol() {
                    num_packages++;
                    $("#volumes").val(num_packages);
                    $("#block_voltocopy").clone().appendTo("#num_vols");
                    do_volume_num();
                }
                function remove_vol() {
                    num_packages--;
                    $("#volumes").val(num_packages);
                    $("#num_vols").children("form[id=block_voltocopy]:last").remove();
                    do_volume_num();
                }
                $(document).ready(function () {
                    $('#cliente').val($('#pc_shipping_fullname').text());
                    $('#endereço').val($('#pc_shipping_address1').text() + ' ' + $('#pc_shipping_address2').text());
                    $('#codpostal').val($('#pc_shipping_postcode').text());
                    $('#cidade').val($('#pc_shipping_cityname').text());
                    $('#pais').val($('#pc_shipping_country').text());
                    $('#telemovel').val($('#pc_shipping_phone').text());
                    //$('#email').val($('#pc_shipping_email').text());
                    // $('#num_packages').text(num_packages);
                    $('#volumes').val(num_packages);
                    do_volume_num();
                    function final_print() {
                        $("#page").each(function(){
                            $('.transportadora_result').html($('#transportadora').val());
                            /* $('.resultv').html($('#volumes').val()); */
                            $('.result').html($('#encomenda').val());
                            $('.result1').html($('#cliente').val());
                            $('.result2').html($('#endereço').val());
                            $('.result3').html($('#codpostal').val());
                            $('.result4').html($('#cidade').val());
                            $('.result5').html($('#pais').val());
                            $('.result7').html("Tel. " + $('#telemovel').val());
                            $('.result8').html($('#email').val());
                            $('.result9').html($('#peso').val());
                            $('.result11').html($('#msg').val());
                            $('.result12').html($('#comprimento').val());
                            $('.result13').html($('#largura').val());
                            $('.result14').html($('#altura').val());
                        });
                        func_copy_inputs();
                        window.print();
                    }
                    $('#submitform').click(function () {
                        final_print();
                    });
                    $('#submitformsec').click(function () {
                        final_print();
                    });
                });
            </script>
        </div>
    </div>