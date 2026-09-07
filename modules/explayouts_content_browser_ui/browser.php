<?php
eZDebug::updateSettings( array( 'debug-enabled' => false ) );
$http = eZHTTPTool::instance();
$module = $Params['Module'];
$locationNodeId = isset( $Params['LocationNodeID'] ) ? (int)$Params['LocationNodeID'] : 1;

// The vhost rewrite does not append the query string to index.php, so $_GET is empty.
// Read the query variables directly from the original REQUEST_URI.
$requestQuery = array();
if ( isset( $_SERVER['REQUEST_URI'] ) )
{
    $queryString = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
    if ( $queryString !== null && $queryString !== '' )
        parse_str( $queryString, $requestQuery );
}

$nglQuery = function( $key, $default = '' ) use ( &$requestQuery )
{
    return isset( $requestQuery[$key] ) ? $requestQuery[$key] : $default;
};

$search = trim( $nglQuery( 'Search', '' ) );
$offset = (int)$nglQuery( 'offset', 0 );
$action = trim( $nglQuery( 'action', '' ) );
$selectedNodeId = (int)$nglQuery( 'selected_node_id', 0 );
$returnUri = trim( $nglQuery( 'return_uri', '' ) );
$field = trim( $nglQuery( 'field', '' ) );
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

            if ( $returnUri === 'js' )
            {
                $tpl = eZTemplate::factory();
                $tpl->setVariable( 'selected_item', $selectedItem );
                $tpl->setVariable( 'field', $field );

                $Result = array();
                $Result['content'] = $tpl->fetch( 'design:explayouts_content_browser_ui/js_callback.tpl' );
                $Result['pagelayout'] = false;
                return $Result;
            }

            $queryParams = array(
                'selected_node_id' => $selectedItem['node_id'],
                'selected_object_id' => $selectedItem['object_id'],
                'selected_name' => $selectedItem['name'],
            );
            if ( $field !== '' )
                $queryParams['field'] = $field;

            $separator = strpos( $returnUri, '?' ) === false ? '?' : '&';
            $redirectUrl = $returnUri . $separator . http_build_query( $queryParams );

            return $module->redirectTo( $redirectUrl );
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

$parentNodeId = 0;
if ( $locationNodeId > 1 )
{
    $currentNode = eZContentObjectTreeNode::fetch( $locationNodeId );
    if ( $currentNode instanceof eZContentObjectTreeNode )
        $parentNodeId = (int)$currentNode->attribute( 'parent_node_id' );
}
$tpl->setVariable( 'parent_node_id', $parentNodeId );
$tpl->setVariable( 'search', $search );
$tpl->setVariable( 'offset', $offset );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'next_offset', $offset + $limit );
$tpl->setVariable( 'has_next', $total > $offset + $limit );
$tpl->setVariable( 'previous_offset', max( 0, $offset - $limit ) );
$tpl->setVariable( 'has_previous', $offset > 0 );
$tpl->setVariable( 'field', $field );
$tpl->setVariable( 'return_uri', $returnUri );
$tpl->setVariable( 'root_node_id', 1 );
$tpl->setVariable( 'has_selection', $selectedItem !== null );
$tpl->setVariable( 'selected_item', $selectedItem !== null ? $selectedItem : array() );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:explayouts_content_browser_ui/browser.tpl' );
return $Result;
