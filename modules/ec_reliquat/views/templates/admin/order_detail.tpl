

<div id="ec_reliquat_orderdetail" class="box hidden-sm-down">



    <table id="order-products" class="table table-bordered">



        <thead class="thead-default">



            <tr>



                <th>{l s='Partial Shipment' mod='ec_reliquat'}</th>



                <th style="text-align: center;">{l s='Tracking number' mod='ec_reliquat'}</th>



                <th>{l s='Carrier' mod='ec_reliquat'}</th>



                <th>{l s='Order State' mod='ec_reliquat'}</th>



                <th>{l s='Delivery Slip' mod='ec_reliquat'}</th>



                <th>Colisage</th>


                <!--
                <th>{l s='Attachments' mod='ec_reliquat'}</th>
                -->


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



                    <td>Colis : {$num_packages_f}/{$reliquats|@count}</td>



                    <td style="text-align: center;">
                        {if isset($reliquat.tracking_url)}
                            <a style="color:#ef5f23;text-decoration: underline;" target="_blank" href="{$reliquat.tracking_url}">
                                {$reliquat.tracking_number}<img style="width: 30px; margin: 5px;" src="https://www.netbricolage.com/img/trackingicon.png"/>
                            </a>
                        {else if isset($reliquat.num_albaran)}
                            <a style="color:#ef5f23;text-decoration: underline;" target="_blank" href="https://parcelsapp.com/fr/tracking/{$reliquat.num_albaran}">
                                {$reliquat.num_albaran}<img style="width: 30px; margin: 5px;" src="https://www.netbricolage.com/img/trackingicon.png"/>
                            </a>
                        {/if}
                    </td>


                    <td>{$reliquat.carrier}</td>



                    <td>{$reliquat.order_state}</td>



                    <td>{$reliquat.date_add} <a href="{$link_delivery_slip}&id_order={$reliquat.id_order}&id_reliquat={$reliquat.id_reliquat}"><i class="material-icons">cloud_download</i></a></td>



                    <td><a href="#" data-id="{$reliquat.id_reliquat}" class="showProdsReliquat"><i class="material-icons">add</i></a></td>


                    <!--
                    <td>{if count($reliquat['attachments']) > 0}<a href="#" data-id="{$reliquat.id_reliquat}" class="showAttsReliquat"><i class="material-icons">add</i></a>{/if}</td>
                    -->


                </tr>



                <tr style="width: 100%; display: none; background-color: #ff4b005e" class="table reliquat_products" id="products{$reliquat.id_reliquat}">
                    <td colspan="6">
                        <table style="width: 100%; background-color: #ffffff" >
                            <thead class="thead-default">
                                <tr>
                                    <th style="width:10%;">&nbsp;</th>
                                    <th style="width:70%;"><span class="title_box ">{l s='Product' mod='ec_reliquat'}</span></th>
                                    <th style="width:20%;"><span class="title_box ">{l s='Quantity' mod='ec_reliquat'}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach $reliquat['products'] as $product}
                                <tr>
                                    <td>
                                        <img src="../img/tmp/product_mini_{$product['product_id']}_{$product['product_attribute_id']}.jpg?time=1554738725" alt="" class="imgm img-thumbnail">
                                    </td>
                                    <td>
                                        <strong>{$product['product_name']}</strong><br>
                                        {$product['product_reference']}<br>                                                            
                                    </td>
                                    <td>
                                        <span>{$product['quantity']}</span>
                                    </td>                       
                                </tr>
                            {/foreach}
                            </tbody>
                            <tfoot>
                                <tr style="background: none;">
                                    <td style="text-align: right; font-weight: bold;" colspan="2"><span class="title_box ">Taille</span></td>
                                    <td style="border-left: 1px solid #d6d4d4;" colspan="1">
                                        <span>{$reliquat.comprimento}cm x {$reliquat.largura}cm x {$reliquat.altura}cm</span>
                                    </td>
                                </tr>
                                <tr style="background: none;">
                                    <td style="text-align: right; font-weight: bold;" colspan="2"><span class="title_box ">Poids</span></td>
                                    <td style="border-left: 1px solid #d6d4d4;" colspan="1">
                                        <span>{$reliquat.peso} Kg</span>
                                    </td> 
                                </tr>
                            </tfoot> 
                        </table>
                    </td> 
                </tr>

                <tr  style="width: 100%; display: none;" class="table reliquat_attachments" id="attachments{$reliquat.id_reliquat}">
                    <td colspan="6">
                        <table style="width: 100%;" >
                            <thead class="thead-default">
                                <tr>
                                    <th style="width:50%;">{l s='File name' mod='ec_reliquat'}</th>
                                    <th style="width:50%;">{l s='Download' mod='ec_reliquat'}</th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach $reliquat['attachments'] as $attachment}
                                <tr>
                                    <td>
                                        {$attachment['name']}.{$attachment['extension']}                                                            
                                    </td>
                                    <td>
                                        <a href="{$dl_script}&k={$attachment['cle']}&front=1"><i class="material-icons">cloud_download</i></a>
                                    </td>                        
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </td> 
                </tr>
            {/foreach}
        </tbody>
    </table>
</div>


<script>
    var num_packages_m = 0;
</script>



{foreach $reliquats as $reliquat}
    <div id="ec_reliquat_orderdetail" class="box hidden-md-up" style="padding: 1rem 0 0 0;border: 5px solid #f9f9f9;">
        <div style="margin: 0 1rem 1rem 1rem;">            
            <script>
                num_packages_m++;             
            </script>

            <span style="display:none;">{$num_packages_m++}</span>
                
            <table style="width: 100%;">
                <tbody>
                    <tr class="trow">
                        <td colspan="2" style="text-align: right;padding-bottom: 10px;"><strong>Colisage : </strong>{$num_packages_m}/{$reliquats|@count}</td>
                    </tr>                    
                    <tr class="trow">                        
                        <td style="width: 50%;">
                            <strong>Date : </strong>{dateFormat date=$reliquat.date_add full=false}
                        </td>
                        <td rowspan="2" style="vertical-align: top;">
                            <div style="text-align: right; display: block ruby;">
                                {if isset($reliquat.tracking_url)}
                                    <div class="button-85" role="button">
                                        <a style="color:#ef5f23; padding-left: 8px;" target="_blank" href="{$reliquat.tracking_url}">
                                            {$reliquat.tracking_number}<img style="width: 30px; margin: 5px;" src="https://www.netbricolage.com/img/trackingicon.png"/>
                                        </a>
                                    </div>
                                {else if isset($reliquat.num_albaran)}
                                    <div class="button-85" role="button">
                                        <a style="color:#ef5f23; padding-left: 8px;" target="_blank" href="https://parcelsapp.com/fr/tracking/{$reliquat.num_albaran}">
                                            {$reliquat.num_albaran}<img style="width: 30px; margin: 5px;" src="https://www.netbricolage.com/img/trackingicon.png"/>
                                        </a>
                                    </div>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    <tr class="trow">
                        <td style="padding: 5px 0;"><strong>{l s='Carrier' mod='ec_reliquat'} : </strong>{$reliquat.carrier}</td>
                    </tr>
                    <tr style="padding: 5px 0;" class="trow">
                        <td><strong>Taille : </strong>{$reliquat.comprimento}x{$reliquat.largura}x{$reliquat.altura} cm</td>                   
                        <td rowspan="2">
                            <div style="text-align: right; display: block ruby;">
                                <div class="button-85" role="button">
                                    <a target="_blank" href="{$link_delivery_slip}&id_order={$reliquat.id_order}&id_reliquat={$reliquat.id_reliquat}">
                                        {l s='Delivery Slip' mod='ec_reliquat'}
                                        <i style="padding: 6px 0;height: 40px;" class="material-icons">cloud_download</i>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style="padding: 5px 0;" class="trow">
                        <td><strong>Poids : </strong>{$reliquat.peso} Kg</td>
                    </tr>
                </tbody>            
            </table>
        </div>
        <div style="background: #ffffff;padding: 0 5px;">
            <table style="width: 100%;">
                <tbody>
                {foreach $reliquat['products'] as $product}
                    <tr style="border-bottom: 1px dotted; height: 100px;">
                        <td style="min-width: 60px;">
                            <img style="border: none;" src="../img/tmp/product_mini_{$product['product_id']}_{$product['product_attribute_id']}.jpg?time=1554738725" alt="" class="imgm img-thumbnail">
                        </td>
                        <td style="padding: 5px;">
                            <strong>{$product['product_name']}</strong><br>
                            {$product['product_reference']}
                        </td>
                        <td style="min-width: 50px;text-align: center;">
                            x{$product['quantity']}
                        </td>                  
                    </tr>
                {/foreach}
                </tbody>                
            </table>        
        </div>
    </div>
{/foreach}
        