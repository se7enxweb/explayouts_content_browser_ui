<?php
eZDebug::updateSettings( array( 'debug-enabled' => false ) );
$http = eZHTTPTool::instance();
$module = $Params['Module'];
$locationNodeId = isset( $Params['LocationNodeID'] ) ? (int)$Params['LocationNodeID'] : 2;
$search = trim( $http->getVariable( 'Search', '' ) );
$offset = (int)$http->getVariable( 'offset', 0 );
$action = trim( $http->getVariable( 'action', '' ) );
$selectedNodeId = (int)$http->getVariable( 'selected_node_id', 0 );
$returnUri = trim( $http->getVariable( 'return_uri', '' ) );
$limit = 25;

// No class filter here. The content browser should show all content by default.
$allowedTypes = array();
$backend = new expLayoutsContentBrowserCoreBackend( $allowedTypes, array( 'folder' ) );

$selectedItem = null;
if ( $action === 'select' && $selectedNodeId > 0 )
{
    $item = $backend->loadItem( $selectedNodeId );
    if ( $item instanceof expLayoutsContentBrowserItem )
    {
        $selectedItem = $item->toArray();

        if ( $returnUri !== '' )
        {
            $returnUri = str_replace( '&amp;', '&', $returnUri );
            return eZHTTPTool::redirect(
                $returnUri,
                array(
                    'selected_node_id' => $selectedItem['node_id'],
                    'selected_object_id' => $selectedItem['object_id'],
                    'selected_name' => $selectedItem['name'],
                )
            );
        }
    }
}

if ( $search !== '' )
{
    $items = $backend->searchItems( $search, $locationNodeId, $offset, $limit );
    $total = $backend->searchItemsCount( $search, $locationNodeId );
}
else
{
    $items = $backend->getSubItems( $locationNodeId, $offset, $limit );
    $total = $backend->getSubItemsCount( $locationNodeId );
}

$tpl = eZTemplate::factory();
$tpl->setVariable( 'items', array_map( function( $item ) { return $item->toArray(); }, $items ) );
$tpl->setVariable( 'total', $total );
$tpl->setVariable( 'location_node_id', $locationNodeId );
$tpl->setVariable( 'search', $search );
$tpl->setVariable( 'offset', $offset );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'next_offset', $offset + $limit );
$tpl->setVariable( 'has_next', $total > $offset + $limit );
$tpl->setVariable( 'previous_offset', max( 0, $offset - $limit ) );
$tpl->setVariable( 'has_previous', $offset > 0 );
$tpl->setVariable( 'return_uri', $returnUri );
$tpl->setVariable( 'has_selection', $selectedItem !== null );
$tpl->setVariable( 'selected_item', $selectedItem !== null ? $selectedItem : array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:explayouts_content_browser_ui/browser.tpl' );
return $Result;
