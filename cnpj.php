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
 * JFormRule for com_component to make sure the CNPJ if is valid.
 *
 * @package     Component
 * @subpackage  com_component
 * @since       3.1
 */
class JFormRuleCnpj extends JFormRule
{
	/**
	 * Method to test for a valid CNPJ.
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
	 *
	 * @since   3.1
	 */
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		// Initialiase variables.
		$cnpj = preg_replace('/[^0-9]/', '', $value);

		if (strlen($cnpj) <> 14)
		{
			return false;
		}

		$calcOne = 0;
		$calcTwo = 0;

		// Check first digit.
		for ($i = 0, $x = 5; $i <= 11; $i++, $x--)
		{
			$x         = ($x < 2) ? 9 : $x;
			$numberOne = substr($cnpj, $i, 1);
			$calcOne   += $numberOne * $x;
		}

		// Check second digit.
		for ($i = 0, $x = 6; $i <= 12; $i++, $x--)
		{
			$x         = ($x < 2) ? 9 : $x;
			$numberTwo = substr($cnpj, $i, 1);
			$calcTwo   += $numberTwo * $x;
		}

		$digitOne = (($calcOne % 11) < 2) ? 0 : 11 - ($calcOne % 11);
		$digitTwo = (($calcTwo % 11) < 2) ? 0 : 11 - ($calcTwo % 11);

		// Test the CNPJ if is valid.
		if ($digitOne <> substr($cnpj, 12, 1) || $digitTwo <> substr($cnpj, 13, 1))
		{
			return false;
		}

		return true;
	}
}
