<?php
/**
 * @package     Component
 * @subpackage  com_component
 *
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * JFormRule for com_component to make sure the CPF if is valid.
 *
 * @package     Component
 * @subpackage  com_component
 * @since       3.1
 */
class JFormRuleCpf extends JFormRule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var     string
	 * @since   3.1
	 */
	protected $regex = '/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/';

	/**
	 * Method to test for a valid CPF.
	 *
	 * @param   SimpleXMLElement  &$element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value     The form field value to validate.
	 * @param   string            $group     The field name group control value. This acts as as an array container for the field.
	 *                                       For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                       full field name would end up being "bar[foo]".
	 * @param   object            &$input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   object            &$form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 */
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		// Test the value against the regular expression.
		if (preg_match($this->regex, $value) == false)
		{
			return false;
		}

		// Initialiase variables.
		$cpf      = preg_replace("/[^0-9]/", "", $value);
		$digitOne = 0;
		$digitTwo = 0;

		// Check first digit.
		for ($i = 0, $x = 10; $i <= 8; $i++, $x--)
		{
			$digitOne += $cpf[$i] * $x;
		}

		$calcOne = (($digitOne % 11) < 2) ? 0 : 11 - ($digitOne % 11);

		// Check second digit.
		for ($i = 0, $x = 11; $i <= 9; $i++, $x--)
		{
			if (str_repeat($i, 11) == $cpf)
			{
				return false;
			}

			$digitTwo += $cpf[$i] * $x;
		}

		$calcTwo = (($digitTwo % 11) < 2) ? 0 : 11 - ($digitTwo % 11);

		// Test the CPF if is valid.
		if ($calcOne <> $cpf[9] || $calcTwo <> $cpf[10])
		{
			return false;
		}

		return true;
	}
}
