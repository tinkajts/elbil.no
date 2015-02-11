<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgSearchIgallery extends JPlugin
{
	function onContentSearchAreas()
	{
		static $areas = array('igallery' => 'Ignite Gallery');
		return $areas;
	}

    function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
        jimport('joomla.filesystem.file');
        require_once(JPATH_ADMINISTRATOR.'/components/com_igallery/defines.php');

		$results = array();
        $text = trim($text);
        $this->counter = 0;

        if( is_array($areas) )
        {
            if( !array_intersect($areas, array_keys( $this->onContentSearchAreas() )) )
            {
                return array();
            }
        }
		
	 	if ($text == '')
		{
			return array();
		}
        
        $results = $this->getImageResults($results, $text, $phrase, $ordering);
        $results = $this->getCategoryResults($results, $text, $phrase, $ordering);

        return $results;
        
    }
    
     function getImageResults($results, $text, $phrase, $ordering)
     {
         $db	= JFactory::getDBO();
         $user = JFactory::getUser();
         $limit = $this->params->def('search_limit', 50);
         
         $query = $db->getQuery(true);

         $query->select('i.*');
         $query->from('#__igallery_img AS i');

         $query->select('c.name');
         $query->join('INNER', '`#__igallery` AS c ON c.id = i.gallery_id');

         $query->select('p.thumb_pagination_amount, p.thumb_pagination');
         $query->join('INNER', '`#__igallery_profiles` AS p ON p.id = c.profile');

         $query->where('i.published = 1');
         $query->where('i.moderate = 1');

         $nullDate = $db->Quote($db->getNullDate());
         $nowDate = $db->Quote(JFactory::getDate()->toSql());
         $query->where('(i.publish_up = ' . $nullDate . ' OR i.publish_up <= ' . $nowDate . ')');
         $query->where('(i.publish_down = ' . $nullDate . ' OR i.publish_down >= ' . $nowDate . ')');

         $groups	= implode(',', $user->getAuthorisedViewLevels() );
         $query->where('i.access IN ('.$groups.')');

         switch($phrase)
         {
             case 'exact':
                 $text		= $db->Quote('%'.$db->escape($text, true).'%', false);
                 $wheres 	= array();
                 $wheres[] 	= 'i.tags LIKE '.$text;
                 $wheres[] 	= 'i.description LIKE '.$text;
                 $wheres[] 	= 'i.alt_text LIKE '.$text;
                 $wheres[] 	= 'i.filename LIKE '.$text;
                 $where 		= '('.implode(') OR (', $wheres ).')';
                 $query->where($where);
                 break;

             default:
                 $words 	= explode(' ', $text);
                 $wheres = array();

                 foreach($words as $word)
                 {
                     $word		= $db->Quote( '%'.$db->escape($word, true).'%', false );
                     $wordWhere 	= array();
                     $wordWhere[] 	= 'i.tags LIKE '.$word;
                     $wordWhere[] 	= 'i.description LIKE '.$word;
                     $wordWhere[] 	= 'i.alt_text LIKE '.$word;
                     $wordWhere[] 	= 'i.filename LIKE '.$word;
                     $wheres[] 	= implode( ' OR ', $wordWhere );
                 }
                 $where 	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
                 $query->where($where);
         }

         switch ($ordering)
         {
             case 'newest':
                 $order = 'i.date DESC';
                 break;

             case 'oldest':
                 $order = 'i.date ASC';
                 break;

             case 'popular':
                 $order = 'i.hits DESC';
                 break;

             case 'alpha':
                 $order = 'i.filename ASC';
                 break;

             case 'category':
                 $order = 'i.gallery_id';
                 break;

             default:
                 $order = 'i.date DESC';
         }

         $query->order($order);
         $db->setQuery($query, 0, $limit);
         $imageRows = $db->loadObjectList();

         for($i=0; $i<count($imageRows); $i++)
         {
             $row = $imageRows[$i];

             $limitStart = '';
             if($row->thumb_pagination == 1)
             {
                 if($row->ordering > $row->thumb_pagination_amount)
                 {
                     $group = ceil( $row->ordering / $row->thumb_pagination_amount ) - 1;

                     if($group > 0)
                     {
                         $limitStart = '&limitstart='.($group * $row->thumb_pagination_amount);
                     }
                 }
             }

             $fileHashNoExt = JFile::stripExt($row->filename);
             $fileHashNoRef = substr($fileHashNoExt, 0, strrpos($fileHashNoExt, '-') );

             $results[$this->counter]->href = JRoute::_('index.php?option=com_igallery&view=category&igid='.$row->gallery_id.'&Itemid='.igUtilityHelper::getItemid($row->gallery_id).$limitStart.'#!'.$fileHashNoRef);
             $results[$this->counter]->title  = strlen($row->alt_text) > 0 ? html_entity_decode($row->alt_text, ENT_QUOTES, 'UTF-8') : substr($row->filename, 0, strrpos($row->filename, '-')).'.'.JFile::getExt($row->filename);
             $results[$this->counter]->text = 'igalleryimg '.$row->id.' &nbsp;&nbsp;'.$row->description;
             $results[$this->counter]->created = $row->date;
             $results[$this->counter]->browsernav = 0;
             $results[$this->counter]->section = $row->name;

             $this->counter ++;
         }

         return $results;
     }

    function getCategoryResults($results, $text, $phrase, $ordering)
    {
        $db	= JFactory::getDBO();
        $limit = $this->params->def('search_limit', 50);

        $query = $db->getQuery(true);
        $query->select('c.*');
        $query->from('#__igallery AS c');
        $query->where('c.published = 1');
        $query->where('c.moderate = 1');
        $nullDate = $db->Quote($db->getNullDate());
        $nowDate = $db->Quote(JFactory::getDate()->toSql());
        $query->where('(c.publish_up = ' . $nullDate . ' OR c.publish_up <= ' . $nowDate . ')');
        $query->where('(c.publish_down = ' . $nullDate . ' OR c.publish_down >= ' . $nowDate . ')');

        switch ($phrase)
        {
            case 'exact':
                $text		= $db->Quote('%'.$db->escape($text, true).'%', false);
                $wheres 	= array();
                $wheres[] 	= 'name LIKE '.$text;
                $wheres[] 	= 'menu_description LIKE '.$text;
                $wheres[] 	= 'gallery_description LIKE '.$text;
                $where 		= '('.implode(') OR (', $wheres ).')';
                $query->where($where);
                break;

            case 'all':
            case 'any':
            default:
                $words 	= explode(' ', $text);
                $wheres = array();
                foreach ($words as $word)
                {
                    $word		= $db->Quote('%'.$db->escape($word, true).'%', false);
                    $wordWhere 	= array();
                    $wordWhere[] 	= 'name LIKE '.$word;
                    $wordWhere[] 	= 'menu_description LIKE '.$word;
                    $wordWhere[] 	= 'gallery_description LIKE '.$word;
                    $wheres[] 	= implode( ' OR ', $wordWhere );
                }
                $where 	= '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
                $query->where($where);
        }

        switch ($ordering)
        {
            case 'newest':
                $order = 'date DESC';
                break;

            case 'oldest':
                $order = 'date ASC';
                break;

            case 'popular':
                $order = 'hits DESC';
                break;

            case 'alpha':
                $order = 'name ASC';
                break;

            case 'category':
                $order = 'parent, ordering';
                break;

            default:
                $order = 'date DESC';
        }

        $query->order($order);
        $db->setQuery($query, 0, $limit);
        $categoryRows = $db->loadObjectList();

        for($i=0; $i<count($categoryRows); $i++)
        {
            $category = $categoryRows[$i];

            $results[$this->counter]->href = JRoute::_('index.php?option=com_igallery&view=category&igid='.$category->id.'&Itemid='.igUtilityHelper::getItemid($category->id));
            $results[$this->counter]->title  = $category->name;
            $results[$this->counter]->text = strlen($category->gallery_description) > 0 ? $category->gallery_description : $category->menu_description;
            $results[$this->counter]->created = $category->date;
            $results[$this->counter]->browsernav = 0;
            $results[$this->counter]->section = '';

            $this->counter ++;
        }

        return $results;
    }
}
