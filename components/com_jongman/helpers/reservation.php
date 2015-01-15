<?php
/**
 * @version: $Id$
 */

defined('_JEXEC') or die;

class ReservationHelper 
{
	public static function canDelete($record) 
	{
		$result = false;
		if ($record->id == 0) return false;
		
		$user = JFactory::getUser();
		if ($user->authorise('core.delete', 'com_jongman.resource.'.$record->resource_id)) 
		{
			return true;
		}

		if (($record->reserved_for == $user->id) || ($record->created_by == $user->id))
		{
			return true;	
		}
		
		return $result;
	}	
}