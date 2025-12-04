<div class="card mt-2 panc_donotprint">
    <div class="card-header">
        <h3 class="card-header-title">
            <i class="material-icons">local_shipping</i> Multi tracking
        </h3>
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-head-del"> - </th>
                    <th class="table-head-date">Date</th>
                    <th class="table-head-carrier">Carrier</th>
                    <th class="table-head-weight">Weight</th>
                    <th class="table-head-trackingnum">Nº Tracking</th>
                    <th class="table-head-none"> - </th>
                </tr>
            </thead>
            <tbody> 
                {foreach from=$getdbdata item=multitrackingdbdata}
                    <form id="update_panctracking" action="" name="panc_multitracking" class="form-horizontal">
                        <tr id="copy" class="d-print-none">
                            <input type="hidden" name="id_panc_multitracking" value="{$multitrackingdbdata.id_panc_multitracking}"/>           
                            <input type="hidden" name="order_id" value="{$multitrackingdbdata.order_id}"/>
                            <td style="width:50px;">
                                <button type="submit" name="submit_deleteform" class="btn btn-primary btn-sm" style="background-color: #ff5151;border: 1px solid #ff5151;"><i class="material-icons">delete</i></button>
                            </td>
                            <td>
                                <div class="input-group datepicker">
                                    <input type="text" class="form-control" data-format="YYYY-MM-DD H:m:s" name="order_tracking_date" required="required" value="{$multitrackingdbdata.date}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="material-icons">date_range</i>
                                        </div>
                                    </div>
                                </div>
                            </td>

                           <td>
                                <select name="order_tracking_carrier" class="custom-select">
                                    <option value=""></option>
                                    {foreach from=$carriers item=carrier}                                    
                                        <option value="{$carrier.id_carrier}" {if $multitrackingdbdata.carrier === $carrier.id_carrier}selected{/if}>{$carrier.name}</option>
                                    {/foreach}
                                </select>
                            </td>

                            <td>              
                                <input type="text" name="order_tracking_weight" class="form-control" value="{$multitrackingdbdata.weight}">
                            </td>

                            <td>

                                <input type="text" name="order_tracking_number" required="required" class="form-control" value="{$multitrackingdbdata.tracking}">

                            </td>

                            <td>
                                <input type="hidden" name="_token" value="{$token}"/>
                                <button type="submit" name="submit_updateform" class="btn btn-primary btn-sm">Update</button>
                            </td>

                        </tr>

                    </form>

                {/foreach}               

                    <tr id="copy" class="d-print-none">

                     <form id="submit_requestform" action="" name="panc_multitracking" class="form-horizontal">

                            <input type="hidden" name="order_id" value="{$id_order}"/>

                            <td style="width:50px;">

                            </td>

                            <td>

                                <div class="input-group datepicker">

                                    <input type="text" class="form-control" data-format="YYYY-MM-DD H:m:s" name="order_tracking_date" required="required">

                                    <div class="input-group-append">

                                        <div class="input-group-text">

                                            <i class="material-icons">date_range</i>

                                        </div>

                                    </div>

                                </div>

                            </td>

                            <td>              

                                <select name="order_tracking_carrier" class="custom-select">

                                    <option value=""></option>

                                    {foreach from=$carriers item=carrier}                                    

                                        <option value="{$carrier.id_carrier}">{$carrier.name} </option>

                                    {/foreach}

                                </select>

                            </td>

                            <td>              

                                <input type="text" name="order_tracking_weight" class="form-control">

                            </td>

                            <td>

                                <input type="text" name="order_tracking_number" required="required" class="form-control">

                            </td>

                            <td>

                                <button type="submit" name="submit_requestform" class="btn btn-primary btn-sm">Add</button>

                            </td>

                        </form>

                    </tr>                

            </tbody>

        </table>        

    </div>

</div>

