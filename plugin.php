<?php 
/* MyOmeka Plugin */

define('MYOMEKA_PLUGIN_VERSION', '0.2dev');

add_plugin_hook('initialize', 'myomeka_initialize');
add_plugin_hook('install', 'myomeka_install');
add_plugin_hook('theme_header', 'myomeka_css');

add_controllers('controllers');

function myomeka_initialize()
{	
	add_theme_pages('theme', 'public');
	
	//Define some special ACL rules for this plugin
		
	$acl = Zend_registry::get( 'acl' );

	//Come up with some terminology for this
	$acl->addRole(new Zend_Acl_Role('myomeka'));

	$acl->registerRule(new Zend_Acl_Resource('MyOmeka'), array('favorite'));

	//The new role and all the existing roles, should be able to list certain items as 'favorites'
	$acl->allow('myomeka',	'MyOmeka',array('favorite'));	
	$acl->allow('researcher',	'MyOmeka',array('favorite'));
	$acl->allow('admin',		'MyOmeka',array('favorite'));
	$acl->allow('contributor',	'MyOmeka',array('favorite'));		
}

function myomeka_install()
{	
	set_option('myomeka_plugin_version', MYOMEKA_PLUGIN_VERSION);
	
	// Create new tables to support poster building
	$db = get_db();
	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}posters ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `title` VARCHAR(255) NOT NULL, 
            	    `description` TEXT, 
            		`user_id` BIGINT UNSIGNED NOT NULL,
            		`date_created` TIMESTAMP NOT NULL default '0000-00-00 00:00:00',
            		`date_modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
            	) ENGINE = MYISAM;");
	
	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}posters_items ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `annotation` TEXT, 
            		`poster_id` BIGINT UNSIGNED NOT NULL,
            		`item_id` BIGINT UNSIGNED NOT NULL,
            		`ordernum` INT NOT NULL
            	) ENGINE = MYISAM;");

	$db->exec(  "CREATE TABLE IF NOT EXISTS {$db->prefix}favorites ( 
                    `id` BIGINT UNSIGNED NOT NULL auto_increment PRIMARY KEY, 
            	    `annotation` TEXT, 
            		`user_id` BIGINT UNSIGNED NOT NULL,
            		`item_id` BIGINT UNSIGNED NOT NULL,
            		`date_modified` TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
            	) ENGINE = MYISAM;");
}

function myomeka_css()
{
	$path = WEB_DIR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'MyOmeka'.DIRECTORY_SEPARATOR.'theme'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'myomeka.css';
	echo "<link rel=\"stylesheet\" media=\"screen\" href=\"$path\" />";
}

add_plugin_hook('item_browse_sql', 'myomeka_show_only_my_items');

/**
 * This allows the MyOmeka controller to pass arbitrary parameters when 
 * retrieving items so that we only retrieve items that were added by a user, etc.
 *
 * @return void
 **/

function myomeka_show_only_my_items($select, $params)
{
	$user = current_user();
	
	if($user) {
		$entity_id = (int) $user->entity_id;
		
		//If the controller sets this parameter, we are retrieving items that were added by this user
		if(isset($params['added_by_me']) or isset($params['favorited_by_me'])) {
		
			//Join against the entities_relations table
			$select->innerJoin('entities_relations my_e','my_e.relation_id = i.id');
			$select->innerJoin('entity_relationships my_er', 'my_er.id = my_e.relationship_id');
			$select->where('my_e.type = "Item" AND my_e.entity_id = ' . $entity_id);
			
			if( isset($params['added_by_me']) ) {
				$select->where('my_er.name = "added"');
			}
			elseif( isset($params['favorited_by_me']) ) {
				$select->where('my_er.name = "favorite"');
			}	
		}
		
		if(isset($params['tagged_by_me'])) {
			
			$select->innerJoin('taggings my_tg', 'my_tg.relation_id = i.id');
			
			$select->where('my_tg.type = "Item" AND my_tg.entity_id = ' . $entity_id);
		}	
	}
}

function mystuff_favorite_link($item)
{
	?>
	<style type="text/css" media="screen">
		#favoriting input {font-size: 2em;}
		#favoriting label {clear:both;}
		#favoriting {
		    display:block;
		    clear:both;
		    background-color: #f1c8ba;
		    border: 1px dotted black;
		    margin-bottom:50px;
		    padding-left:30px;
		    padding-top: 30px;
		    padding-bottom: 20px;}
		#favoriting textarea {float: none;clear:both;}
		#saved-annotation {font-style: italic;font-size: 2em;clear:both;}
	</style>
	
	<div id="favoriting">
		<a href="#" id="favorite-off"><img src="<?php echo img('favorite-off.gif'); ?>" /></a>
	</div>
	
	<script type="text/javascript" charset="utf-8">
	    var container = $('favoriting');
	    
		var makeFavorite = function() {
			var url = "<?php echo uri('_favorite_form'); ?>";
			new Ajax.Updater(container, url, {
				onSuccess: function(t) {
					Effect.Appear(container);					
				},
				onComplete: function(t) {
					Event.observe('save-annotation', 'click', saveAnnotation);
				}
			});
			
			return false;
		}
		
		var saveAnnotation = function() {
			var annotation = $('annotation').value;
			var tags = $('tags').value;
			var item_id = <?php echo $item->id; ?>;
			
			//Make a spot on the page for the saved annotation
			
			new Ajax.Updater(container, "<?php echo uri('favorite/add'); ?>", {
			    parameters: {
			        annotation: annotation,
			        tags: tags,
					item_id: item_id
			    },
			    method: 'get',
			    onComplete: function(t) {
			        Event.observe('edit-annotation', 'click', makeFavorite);
			    }
			});
						
			return false;
		}

		Event.observe('favorite-off', 'click', makeFavorite);
	</script>
	
<?php
}

function poster_icon_html($item) {
    //If we can get a square thumbnail out of it, use that
    if($thumbnail = square_thumbnail($item)) {
        return $thumbnail;
    }
    
    switch ($item->Type->name) {
        case 'Document':
            return 'Document';
            break;
        
        default:
            return 'No Type given';
            break;
    }
}

?>