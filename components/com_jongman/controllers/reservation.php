<?php
/**
 * @version: $Id$
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

/**
 * Reservation Subcontroller.
 *
 * @package     JONGman
 * @subpackage  Frontend
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       1.0
 */
class JongmanControllerReservation extends JControllerForm
{
	protected $view_list = 'schedule';
	
	protected $view_item = 'reservation';
	
	public function edit($key = null, $urlVar = null)
	{
		return parent::edit('id', 'id');
	}
	
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = JRequest::getInt($urlVar);

		if (!$this->checkEditId($context, $recordId))
		{
			// Somehow the person just went to the form and tried to save it. We don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}
		// Attempt to validate reservation (conflict or unavailable?)
		if (!$model->validateResource($validData)) 
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);
			
			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('COM_JONGMAN_ERROR_RESOURCE_UNAVAILABLE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');	

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					//. $this->getRedirectToItemAppend($recordId, $key), false
					.'&layout=modal&tmpl=component'.(!empty($recordId)?"&$key=$recordId":""), false
					)
				);
			return false;		
		}
		
		// Attempt to save the data.
		if (!$model->save($validData))
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($validData[$key]) === false)
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $key), false
				)
			);

			return false;
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);
				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $key), false
					)
				);
				
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				
				if (JRequest::getInt('popup') == 1 ) {
					//Redirect to view that will close modal form
					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=closepopup&refresh=1', false
						)
					);				
				} else {
					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_list
							.$this->getRedirectListAppend(), false
							)
					);
				}
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}

	/**
	 * Override to add support to redirect to closepopup view
	 * @see JControllerForm::cancel()
	 */
	public function cancel($key = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";

		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		$recordId = JRequest::getInt($key);

		// Attempt to check-in the current record.
		if ($recordId)
		{
			// Check we are holding the id in the edit list.
			if (!$this->checkEditId($context, $recordId))
			{
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
				$this->setMessage($this->getError(), 'error');
				if (JRequest::getInt('popup') == 1) {
					$this->setRedirect(
						JRoute::_('index.php?option='.$this->option.'&view=closepopup&cancel=1&refresh=0')
					);
				}else {
					$this->setRedirect(
						JRoute::_(
							'index.php?option=' . $this->option . '&view=' . $this->view_list
							. $this->getRedirectToListAppend(), false
						)
					);
				}
				return false;
			}

			if ($checkin)
			{
				if ($model->checkin($recordId) === false)
				{
					// Check-in failed, go back to the record and display a notice.
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
					$this->setMessage($this->getError(), 'error');
					if (JRequest::getInt('popup') == 1) {
						
						$this->setRedirect(
							JRoute::_('index.php?option='.$this->option.'&view=closepopup&cancel=1&refresh=0')
						);
					}else {
						$this->setRedirect(
							JRoute::_(
								'index.php?option=' . $this->option . '&view=' . $this->view_item
								. $this->getRedirectToItemAppend($recordId, $key), false
							)
						);
					}
					return false;
				}
			}
		}

		// Clean the session data and redirect.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);
		if (JRequest::getInt('popup') == 1) {
			$this->setRedirect(
				JRoute::_('index.php?option='.$this->option.'&view=closepopup&cancel=1&refresh=0')
			);
		}else {
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);
		}
		return true;
	}
	
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id') 
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		
		$resource_id = JRequest::getInt('resource_id', null);
		$schedule_id = JRequest::getInt('schedule_id', null);
		$ts = JRequest::getInt('ts', null); //date in unix timestamp format
		$tstart = JRequest::getInt('tstart', null); //start time in format of minutes from 00:00:00
		$tend = JRequest::getInt('tend', null); //end time in format of minutes from 00:00:00
		$is_blackout = JRequest::getInt('is_blackout', null);
		$type = JRequest::getCmd('type', null);
		
		if ($resource_id !== null) {
			$append .= '&resource_id='.$resource_id;
		}
		
		if ($schedule_id !== null) {
			$append .= '&schedule_id='.$schedule_id;
		}
		
		if ($ts !== null) {
			$append .= '&ts='.$ts;
		}
		
		if ($tstart !== null) {
			$append .= '&tstart='.$tstart;
		}
		
		if ($tend !== null) {
			$append .= '&tend='.$tend;
		}
		
		if ($is_blackout !== null) {
			$append .= '&is_blackout='.$is_blackout;
		}
		
		if ($type!== null) {
			$append .= '&type='.$type;
		}
		return $append;
	}
	
	protected function getRedirectToListAppend()
	{

		$append = parent::getRedirectToListAppend();
		
		$schedule_id = JRequest::getInt('schedule_id', null);
		
		return $append.'&layout=calendar&id='.$schedule_id;
		
	}
}