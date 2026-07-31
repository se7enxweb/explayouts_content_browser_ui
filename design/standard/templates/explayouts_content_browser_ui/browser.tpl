{* Content browser UI *}
<div class="content-browser">
    <h2>{'Content browser'|i18n( 'explayouts_content_browser_ui/browser' )}</h2>

    {if $has_selection}
        {if count($selected_item)|gt(0)}
            <div class="selected-item">
                <h3>{'Selected item'|i18n( 'explayouts_content_browser_ui/browser' )}</h3>
                <p>
                    <strong>{$selected_item.name|wash}</strong>
                    (Node: {$selected_item.node_id}, Object: {$selected_item.object_id})
                </p>
            </div>
        {else}
            <div class="error">{'The selected item could not be loaded.'|i18n( 'explayouts_content_browser_ui/browser' )}</div>
        {/if}
    {/if}

    <form method="get" action={'/explayouts_content_browser_ui/browser/'|concat( $location_node_id )|ezurl}>
        <input type="text" name="Search" value="{$search|wash}" />
        <input type="hidden" name="return_uri" value="{$return_uri|wash}" />
        <input type="submit" value="{'Search'|i18n( 'explayouts_content_browser_ui/browser' )}" />
    </form>

    {if $search|ne( '' )}
        <p>{'Search:'|i18n( 'explayouts_content_browser_ui/browser' )} {$search|wash}</p>
    {/if}

    <p>{'Showing'|i18n( 'explayouts_content_browser_ui/browser' )} {$items|count()} {'of'|i18n( 'explayouts_content_browser_ui/browser' )} {$total} {'items'|i18n( 'explayouts_content_browser_ui/browser' )}</p>

    <table class="list" cellspacing="0">
        <thead>
            <tr>
                <th>{'Name'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
                <th>{'Object ID'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
                <th>{'Class'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
                <th>{'Published'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
                <th>{'Modified'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
                <th>{'Owner'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
                <th>{'Actions'|i18n( 'explayouts_content_browser_ui/browser' )}</th>
            </tr>
        </thead>
        <tbody>
            {foreach $items as $item}
                <tr title="{$item.path|wash}">
                    <td>
                        {if $item.is_container}
                            <a href={'/explayouts_content_browser_ui/browser/'|concat( $item.id )|ezurl}>{$item.name|wash}</a>
                            <small>({'container'|i18n( 'explayouts_content_browser_ui/browser' )})</small>
                        {else}
                            {$item.name|wash}
                        {/if}
                    </td>
                    <td>{$item.object_id}</td>
                    <td>{$item.class_name|wash} ({$item.class_identifier|wash})</td>
                    <td>{$item.published}</td>
                    <td>{$item.modified}</td>
                    <td>{$item.owner_name|wash}</td>
                    <td>
                        <a href={'/explayouts_content_browser_ui/browser/'|concat( $location_node_id, '?action=select&selected_node_id=', $item.id, '&return_uri=', $return_uri )|ezurl}>{'Select'|i18n( 'explayouts_content_browser_ui/browser' )}</a>
                        | <a href={'/content/edit/'|concat( $item.object_id )|ezurl}>{'Edit'|i18n( 'explayouts_content_browser_ui/browser' )}</a>
                        | <a href={'/'|concat( $item.url_alias )|ezurl} target="_blank">{'View'|i18n( 'explayouts_content_browser_ui/browser' )}</a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>

    {if or( $has_previous, $has_next )}
        <div class="pagination">
            {if $has_previous}
                <a href={'/explayouts_content_browser_ui/browser/'|concat( $location_node_id, '?offset=', $previous_offset )|ezurl}>{'Previous'|i18n( 'explayouts_content_browser_ui/browser' )}</a>
            {/if}
            {if $has_next}
                <a href={'/explayouts_content_browser_ui/browser/'|concat( $location_node_id, '?offset=', $next_offset )|ezurl}>{'Next'|i18n( 'explayouts_content_browser_ui/browser' )}</a>
            {/if}
        </div>
    {/if}
</div>
