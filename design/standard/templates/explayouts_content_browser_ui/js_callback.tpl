{* Content browser JS callback for popup selection *}
<script type="text/javascript">
{literal}
(function() {
    var nodeId = {/literal}{$selected_item.node_id|wash}{literal};
    var name = '{/literal}{$selected_item.name|wash(javascript)}{literal}';
    var pathWithNames = '{/literal}{$selected_item.path_with_names|wash(javascript)}{literal}';
    var field = '{/literal}{$field|wash(javascript)}{literal}';

    if ( window.opener && typeof window.opener.setRuleTargetValue === 'function' )
    {
        window.opener.setRuleTargetValue( nodeId, name, pathWithNames, field );
    }
    else if ( window.parent && typeof window.parent.setRuleTargetValue === 'function' )
    {
        window.parent.setRuleTargetValue( nodeId, name, pathWithNames, field );
    }

    window.close();
})();
{/literal}
</script>
