<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_quickmail
 * @copyright  2008-2017 Louisiana State University
 * @copyright  2008-2017 Adam Zapletal, Chad Mazilly, Philip Cali, Robert Russo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_quickmail\requests;

use block_quickmail\forms\manage_alternates_form;

// require_once('../../lib/weblib.php');

class alternate_request extends \block_quickmail_request {

    public $form;

    public $form_data;

    public $course;
    
    public static $public_attributes = [
        'firstname',
        'lastname',
        'email',
        'availability',
        'create_flag',
        'delete_alternate_id',
    ];

    /**
     * Construct the message submission request
     * 
     * @param manage_alternates_form  $manage_alternates_form  (extends moodleform)
     */
    public function __construct(manage_alternates_form $manage_alternates_form) {
        $this->form = $manage_alternates_form;
        $this->form_data = ! empty($this->form) ? $this->form->get_data() : null;
        $this->course = $this->get_request_course();
    }

    public function get_request_course() {
        return $this->form->course;
    }
    
    /////////////////////////////////////////////////////////////
    ///
    ///  INSTANTIATION
    ///
    /////////////////////////////////////////////////////////////

    /**
     * Instantiates and returns an alternate email request
     * 
     * @param  \manage_alternates_form   $manage_alternates_form
     * @return \alternate_request
     */
    public static function make(manage_alternates_form $manage_alternates_form) {
        // instantiate "alternate" request
        $request = new self($manage_alternates_form);

        return $request;
    }

    /////////////////////////////////////////////////////////////
    ///
    ///  FORM SUBMISSION ACTIONS
    ///
    /////////////////////////////////////////////////////////////

    /**
     * Helper function to report whether or not the request was submitted with intent to create an alternate
     * 
     * @return bool
     */
    public function to_create_alternate() {
        return $this->has_form_data_matching('create_flag', 1);
    }

    /**
     * Helper function to report whether or not the request was submitted with intent to delete an alternate
     * 
     * @return bool
     */
    public function to_delete_alternate() {
        if ( ! $this->has_form_data_key('delete_alternate_id')) {
            return false;
        }

        return (bool) $this->delete_alternate_id;
    }

    /////////////////////////////////////////////////////////////
    ///
    ///  ATTRIBUTES
    ///
    /////////////////////////////////////////////////////////////
    
    /**
     * Return this (create) requests submitted data as a sanitized object
     * 
     * @return object
     */
    public function get_create_request_data_object() {
        $data = new \stdClass();
        
        $data->user_id = (int) $this->form->user->id;
        $data->course_id = (int) $this->course->id;
        $data->firstname = $this->firstname;
        $data->lastname = $this->lastname;
        $data->email = $this->email;
        $data->availability = $this->availability;

        return $data;
    }

    /**
     * Returns the requested firstname
     * 
     * @return string
     */
    public function firstname($form_data = null) {
        return ! empty($form_data) ? (string) $this->form_data->firstname : '';
    }

    /**
     * Returns the requested lastname
     * 
     * @return string
     */
    public function lastname($form_data = null) {
        return ! empty($form_data) ? (string) $this->form_data->lastname : '';
    }

    /**
     * Returns the requested email
     * 
     * @return string
     */
    public function email($form_data = null) {
        return ! empty($form_data) ? (string) $this->form_data->email : '';
    }

    /**
     * Returns the requested availability
     * 
     * @return string
     */
    public function availability($form_data = null) {
        return ! empty($form_data) ? (string) $this->form_data->availability : '';
    }

    /**
     * Returns an int representing the create flag
     * 
     * @return int
     */
    public function create_flag($form_data = null) {
        if (empty($form_data)) {
            return 0;
        }

        return ! empty($this->form_data->create_flag) ? (int) $this->form_data->create_flag : 0;
    }

    /**
     * Returns an int representing the id of the alternate email to be deleted
     * 
     * @return int
     */
    public function delete_alternate_id($form_data = null) {
        if (empty($form_data)) {
            return 0;
        }

        return ! empty($this->form_data->delete_alternate_id) ? (int) $this->form_data->delete_alternate_id : 0;
    }

}