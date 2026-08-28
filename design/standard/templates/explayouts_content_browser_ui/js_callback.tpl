{* Content browser JS callback for popup selection *}
<script type="text/javascript">
(function() {
    var nodeId = {$selected_item.node_id|wash};
    var name = '{$selected_item.name|wash(javascript)}';
    var field = '{$field|wash(javascript)}';

    if ( window.opener && typeof window.opener.setRuleTargetValue === 'function' )
    {
        window.opener.setRuleTargetValue( nodeId, name, field );
    }
    else if ( window.parent && typeof window.parent.setRuleTargetValue === 'function' )
    {
        window.parent.setRuleTargetValue( nodeId, name, field );
    }

    window.close();
})();
</script>
