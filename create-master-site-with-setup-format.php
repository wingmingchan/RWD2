<?php
/*
This program can be used to create a master site.
Variables to be set before running the program:
$site_name
$site_url
$ms_container_path
$cs_container_path
$cs_name
$config_name
$file_ext
$dd_container_path
$dd_name
$wysiwyg_identifier
$ct_container_path
$ct_name
$block_folder_path
$format_folder_path
$template_folder_path
$calling_page_index_block_name
$template_name
*/
require_once( 'auth_tutorial7.php' );

use cascade_ws_AOHS      as aohs;
use cascade_ws_constants as c;
use cascade_ws_asset     as a;
use cascade_ws_property  as p;
use cascade_ws_utility   as u;
use cascade_ws_exception as e;

$start_time = time();

// flag to control whether to create assets even if the site exists
// when set to true, all involved assets will be created if non-existent
// else nothing will be created if the site exists
$create_assets = true;
$slash         = "/";

try
{
    u\DebugUtility::setTimeSpaceLimits();
    
    // site to be created
    $site_name = "_master-site-for-design-x";
    $site_url  = "webapp.upstate.edu/$site_name";
    
    // metadata set container: full path required
    // could be "/"
    $ms_container_path = "upstate";
    
    if( $ms_container_path != $slash )
        $ms_container_path = trim( $ms_container_path, $slash );
    
    // metadata sets
    $ms_names = array( "Block", "Folder", "Page" );
    
    // configuration set container: full path required
    // could be "/"
    $cs_container_path = "/";

    if( $cs_container_path != $slash )
        $cs_container_path = trim( $cs_container_path, $slash );
        
    // configuration set
    $cs_name     = "RWD";
    $config_name = "RWD";
    $file_ext    = ".php";

    // data definition container: full path required
    // could be "/"
    $dd_container_path = "/";
    
    if( $dd_container_path != $slash )
        $dd_container_path = trim( $dd_container_path, $slash );
        
    // data definition
    $dd_name            = "RWD";
    $wysiwyg_identifier = "main-section-wysiwyg";
    
    // content type container: full path required
    // could be "/"
    $ct_container_path = "/";
    
    if( $ct_container_path != $slash )
        $ct_container_path = trim( $ct_container_path, $slash );
        
    // data definition
    $ct_name           = "RWD";
    
    // folders: full paths
    $block_folder_path    = "blocks/index";
    $block_folder_path    = trim( $block_folder_path, $slash );
    $format_folder_path   = "formats/data";
    $format_folder_path   = trim( $format_folder_path, $slash );
    $template_folder_path = "templates";
    $template_folder_path = trim( $template_folder_path, $slash );
    
    // formats in formats/data
    // add more names here to generate more global components
    $format_names = array(
        "breadcrumbs",
        "contact",
        "default",
        "logo",
        "folder-nav",
        "footer",
        "master-setup",
        "site-nav"
    );
    
    // index block in blocks/index
    $calling_page_index_block_name = "calling-page";
    
    // template
    $template_name = "xml";
    
    try
    {
        $cascade->getSite( $site_name );
        echo "The site $site_name already exists.", BR;
    }
    catch( e\NoSuchSiteException $e )
    {
        $cascade->createSite(
            $site_name,
            $site_url,
            c\T::FIFTEEN // expiration: 15 days
        );
        echo "The site $site_name has been created.", BR;
        // set to true for a newly created site
        $overwrite_assets = true;
    }
    
    if( $create_assets )
    {
        // create metadata set container if non-existent
        $ms_container = createContainerByPath( 
            a\MetadataSetContainer::TYPE, $ms_container_path, $site_name );
    
        // create metadata sets if non-existent
        foreach( $ms_names as $ms_name )
        {
            $ms_path = trim( $ms_container_path, $slash ) . $slash . $ms_name;
            $ms      = $cascade->getMetadataSet( $ms_path, $site_name );
        
            if( is_null( $ms ) )
            {
                $cascade->createMetadataSet( $ms_container, $ms_name );
            }
        }
        
        // create folders if non-existent
        $block_folder = createContainerByPath(
            a\Folder::TYPE, $block_folder_path, $site_name );
        $format_folder = createContainerByPath(
            a\Folder::TYPE, $format_folder_path, $site_name );
        $template_folder = createContainerByPath(
            a\Folder::TYPE, $template_folder_path, $site_name );
            
        // create formats if non-existent
        foreach( $format_names as $format_name )
        {
            $format_path = $format_folder_path . $slash . $format_name;
            $format = $cascade->getScriptFormat( $format_path, $site_name );
            
            if( is_null( $format ) )
            {
                $cascade->createScriptFormat( $format_folder, $format_name, "##" );
            }
        }
        
        // create index block
        $calling_page_index_block = $cascade->getIndexBlock( 
            $block_folder_path . $slash . $calling_page_index_block_name, $site_name );
            
        if( is_null( $calling_page_index_block ) )
        {
            $calling_page_index_block = $cascade->createIndexBlock(
                $block_folder,
                $calling_page_index_block_name,
                a\Folder::TYPE );
        }
        
        $calling_page_index_block->setRenderingBehavior( c\T::HIERARCHYWITHSIBLINGS )->
            setAppendCallingPageData( true )->
            setIndexRegularContent( true )->
            setIndexSystemMetadata( true )->
            setIndexUserMetadata( true )->
            setIndexLinks( true )->
            setIndexPages( true )->
            setPageXML( c\T::RENDERCURRENTPAGEONLY )->
            setSortMethod( c\T::FOLDERORDER )->
            setSortOrder( c\T::ASCENDING )->edit();
        
        // create template
        $template = $cascade->getTemplate(
            $template_folder_path . $slash . $template_name,
            $site_name );
            
        if( is_null( $template ) )
        {
            $template = $cascade->createTemplate(
                $template_folder, $template_name, "<system-region name=\"DEFAULT\"/>" );
        }
        
        // create configuration set containers if non-existent
        $cs_container = createContainerByPath(
            a\PageConfigurationSetContainer::TYPE, $cs_container_path, $site_name );
        
        // create configuration set if non-existent
        $cs = $cascade->getPageConfigurationSet(
            $cs_container_path . $slash . $cs_name, $site_name );
            
        if( is_null( $cs ) )
        {
            $cs = $cascade->createPageConfigurationSet(
                $cs_container, $cs_name, $config_name, $template, $file_ext, c\T::HTML );
        }
        
        $default_format = $cascade->getAsset(
            a\ScriptFormat::TYPE, $format_folder_path . $slash . "default", $site_name );
            
        // attach block and format
        $cs->setConfigurationPageRegionBlock(
                $config_name, "DEFAULT", $calling_page_index_block )->
            setConfigurationPageRegionFormat(
            $config_name, "DEFAULT", $default_format )->
            edit();    
        
        // create data definition containers if non-existent
        $dd_container = createContainerByPath(
            a\DataDefinitionContainer::TYPE, $dd_container_path, $site_name );
        
        // create configuration set if non-existent
        $dd = $cascade->getDataDefinition(
            $dd_container_path . $slash . $dd_name, $site_name );
            
        if( is_null( $dd ) )
        {
            $dd = $cascade->createDataDefinition(
                $dd_container, $dd_name, 
"<system-data-structure>
<text wysiwyg=\"true\" identifier=\"$wysiwyg_identifier\" label=\"Content\"/>
</system-data-structure>"
             );
        }
        
        // create content type containers if non-existent
        $ct_container = createContainerByPath(
            a\ContentTypeContainer::TYPE, $ct_container_path, $site_name );
        
        // create content type if non-existent
        $ct = $cascade->getContentType(
            $ct_container_path . $slash . $ct_name, $site_name );
            
        if( is_null( $ct ) )
        {
            $ms = $cascade->getAsset(
                a\MetadataSet::TYPE, $ms_container_path . $slash . "Page", $site_name );
            
            $ct = $cascade->createContentType(
                $ct_container, $ct_name, $cs, $ms, $dd );
        }
    }
    u\DebugUtility::outputDuration( $start_time );
}
catch( \Exception $e ) 
{
    echo S_PRE . $e . E_PRE; 
}
catch( \Error $er )
{
    echo S_PRE . $er . E_PRE; 
}

/* 
This function uses the type and path information and create containers of the type
and returns the container whose path is identical to $path.
*/
function createContainerByPath( string $type, string $path, string $site_name )
{
    global $cascade, $slash;
    
    if( $path == $slash )
    {
        return $cascade->getAsset( $type, $path, $site_name );
    }
    
    $path_array = u\StringUtility::getExplodedStringArray( $slash, $path );
    $array_size = count( $path_array );
    $parent_path  = "";
    $current_path = "";
    
    for( $i = 0; $i < $array_size; $i++ )
    {
        if( $i == 0 )
            $parent_path = $slash;
        else
            $parent_path = trim( $parent_path . $slash . $path_array[ $i - 1 ], $slash );
            
        $current_path = $current_path . $slash . $path_array[ $i ];
        $current_path = trim( $current_path, $slash );
        $container    = NULL;
        
        try
        {
            $container = $cascade->getAsset( $type, $current_path, $site_name );
        }
        catch( cascade_ws_exception\NullAssetException $e )
        {
            $parent = $cascade->getAsset( $type, $parent_path, $site_name );
            $method_name = cascade_ws_constants\T::$type_property_name_map[ $type ];
            $method_name = "create" . ucwords( $method_name );
            
            $container = $cascade->$method_name(
                $parent, $path_array[ $i ]
            );
        }
    }
    
    return $container;
}
?>