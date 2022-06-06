<!-- page navigation tabs for orders and check24 request list -->
<div class="tabs">
    <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            [{assign var="_cnt" value="0"}]
            [{foreach from=$editnavi item=edit}]

            [{if $edit->getAttribute('active')}]
            [{assign var="_act" value=$edit->getAttribute('id')}]
            [{assign var="_state" value="active"}]
            [{elseif $oxid == "-1" && $edit->getAttribute('id') != 'check24connector_requestlist'}]
            [{assign var="_state" value="disabled"}]
            [{else}]
            [{assign var="_state" value="inactive"}]
            [{/if}]

            [{if $_cnt == 0}]
            [{assign var="_state" value=$_state|cat:" first"}]
            [{/if}]

            [{if $_cnt == $editnavi->length -1}]
            [{assign var="_state" value=$_state|cat:" last"}]
            [{/if}]

            [{if $edit->getAttribute('external') == 'true'}]
            [{assign var="_action" value="ChangeExternal"}]
            [{assign var="_param1" value=$edit->getAttribute('location')}]
            [{else}]
            [{assign var="_action" value=$sEditAction|default:"top.oxid.admin.changeEditBar"}]
            [{assign var="_param1" value=$edit->getAttribute('cl')}]
            [{/if}]

            <td class="tab [{$_state}]">
                <div class="r1">
                    <div class="b1">
                        [{if $edit->getAttribute('id') == 'tbclorder_overview'}]
                            <a href="[{$oViewConf->getSelfLink()}]&cl=admin_order" target="basefrm">[{oxmultilang ident=$edit->getAttribute('id') noerror=true}]</a>
                        [{elseif $edit->getAttribute('id') == 'check24connector_requestlist'}]
                            <a href="#" onclick="top.frames[2].frames[0].location.href='[{$oViewConf->getSelfLink()}]&[{$editurl}]&cl=check24connector_requestlist';top.frames[2].frames[1].location.href='[{$oViewConf->getSelfLink()}]&[{$editurl}]&cl=check24connector_requestoverview';">
                                [{oxmultilang ident=$edit->getAttribute('id') noerror=true}]
                            </a>
                        [{elseif $_state == 'disabled'}]
                            [{oxmultilang ident=$edit->getAttribute('id') noerror=true}]
                        [{else}]
                            <a href="#[{$_param1}]" onclick="[{$_action}]('[{$_param1}]',[{$_cnt}]);return false;">
                                [{oxmultilang ident=$edit->getAttribute('id') noerror=true}]
                            </a>
                        [{/if}]
                    </div>
                </div>
            </td>

            [{assign var="_cnt" value=$_cnt+1}]
            [{/foreach}]
            <td class="line"></td>
        </tr>
    </table>
</div>