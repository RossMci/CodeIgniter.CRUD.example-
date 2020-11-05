<?php

class AddressBook extends CI_Model
{

	function addEntryMaster()
	{
		$master_data['date_added'] = date('Y-m-d');
		$master_data['date_modified'] = date('Y-m-d');
		$master_data['f_name'] = $this->input->post('f_name');
		$master_data['l_name'] = $this->input->post('l_name');

		return $this->db->insert('master_name', $master_data);
	}

	function addEntryAddress($insert_id)
	{
		$address_data['master_id'] = $insert_id;
		$address_data['date_added'] = date('Y-m-d');
		$address_data['date_modified'] = date('Y-m-d');
		$address_data['address'] = $this->input->post('address');
		$address_data['city'] = $this->input->post('city');
		$address_data['town'] = $this->input->post('town');
		$address_data['type'] = $this->input->post('add_type');

		$this->db->insert('address', $address_data);
	}

	function addEntryTelephone($insert_id)
	{
		$tel_data['master_id'] = $insert_id;
		$tel_data['date_added'] = date('Y-m-d');
		$tel_data['date_modified'] = date('Y-m-d');
		$tel_data['telephoneNo'] = $this->input->post('tel_number');
		$tel_data['type'] = $this->input->post('tel_type');

		$this->db->insert('telephone', $tel_data);
	}

	function addEntryEmail($insert_id)
	{
		$email_data['master_id'] = $insert_id;
		$email_data['date_added'] = date('Y-m-d');
		$email_data['date_modified'] = date('Y-m-d');
		$email_data['email'] = $this->input->post('email');
		$email_data['type'] = $this->input->post('email_type');


		$this->db->insert('email', $email_data);
	}

	//Adds new Fax details to the Fax table
	function addEntryFax($insert_id)
	{
		//Inserts email details from form into associative array 
		// with keys same name as database fields
		$fax_data['master_id'] = $insert_id;
		$fax_data['fax_number'] = $this->input->post('fax');
		$fax_data['type'] = $this->input->post('fax_type');
		$fax_data['date_added'] = date('Y-m-d');
		$fax_data['date_modified'] = date('Y-m-d');

		$this->db->insert('fax', $fax_data);	// returns True or False whether the insert was correct
	}

	//Adds new Notes details to the Personal Notes table
	function addEntryNotes($insert_id)
	{
		//Inserts email details from form into associative array with keys 
		//same name as database fields
		$note_data['master_id'] = $insert_id;
		$note_data['note'] = $this->input->post('note');
		$note_data['date_added'] = date('Y-m-d');
		$note_data['date_modified'] = date('Y-m-d');

		$this->db->insert('Personal_Notes', $note_data);	// returns True or False whether the insert was correct
	}

	function selectContacts()
	{
		$display_block = ""; //used to build the option values for the dropdown list box 
		$contacts = $this->db->select("master_id, CONCAT(l_name,' ', f_name) AS display_name");
		$query = $this->db->get('master_name');
		//If rows in master_name table exist
		if ($query->num_rows() > 0)
		{
			foreach ($query->result_array() as $contact)
			{		//For each entry        
				$id = $contact['master_id'];
				$display_name = stripslashes($contact['display_name']);
				//Sets the value and the text to display for the select list on the view 
				$display_block .= "<option value=\"" . $id . "\">" . $display_name . "</option>";
			}
		}
		else
		{
			$display_block .= "<option>No Contacts to Select</option>";
		}
		return $display_block;
	}

	function getSelectedContact($master_id)
	{
		$display_block2 = "";

		if ($master_id == null)
		{
			$display_block2 = "<div class='error'> Please choose a record </div>";
		}
		else
		{
			//Get the Contact Names
			$this->db->select("master_id, CONCAT(l_name,' ', f_name) AS display_name");
			$query = $this->db->get_where('master_name', array('master_id' => $master_id));

			if ($query->num_rows() > 0)
			{

				$result = $query->row();
				$display_block2 .= "<h3>Showing Record for: " . $result->display_name . "</h3>";
			}
			else
			{

				$display_block2 = "<div class='error'>Selected Contact not found in Address Book</div>";
			}
			//Get the Contact Address Details   

			$this->db->select("address, city, town, type", false);
			$query = $this->db->get_where('address', array('master_id' => $master_id));


			if ($query->num_rows() > 0)
			{

				$result = $query->row();
				$display_block2 .= 'Address: ' . $result->address . ',  ' . $result->city .
						',  ' . $result->town . ',  (' . $result->type . ')</br>';
			}
			else
			{

				$display_block2 .= "<p class='error'>Selected Contact does not have an address</p>";
			}



			//Get the Contact Telephone Details            
			$this->db->select("telephoneNo, type", false);
			$query = $this->db->get_where('telephone', array('master_id' => $master_id));

			if ($query->num_rows() > 0)
			{
				$result = $query->row();
				$display_block2 .= 'Telephone: ' . $result->telephoneNo . ' (' . $result->type . ')</br>';
			}
			else
			{

				$display_block2 .= "<p class='error'>Selected Contact does not have telephone number</p>";
			}

			//Get the Contact Email Details           
			$this->db->select("email, type", false);
			$query = $this->db->get_where('email', array('master_id' => $master_id));

			if ($query->num_rows() > 0)
			{
				$result = $query->row();
				$display_block2 .= 'Email: ' . $result->email . '(    ' . $result->type . ')</br>';
			}
			else
			{

				$display_block2 .= "<p class='error'>Selected Contact does not have an email</p>";
			}

			//Get the Contact Fax Details           
			$this->db->select("fax_number, type", false);
			$query = $this->db->get_where('fax', array('master_id' => $master_id));

			if ($query->num_rows() > 0)
			{
				$result = $query->row();
				$display_block2 .= 'fax: ' . $result->fax_number . '(    ' . $result->type . ')</br>';
			}
			else
			{

				$display_block2 .= "<p class='error'>Selected Contact does not have a fax</p>";
			}

			//Get the Contact note Details           
			$this->db->select("note", false);
			$query = $this->db->get_where('personal_notes', array('master_id' => $master_id));

			if ($query->num_rows() > 0)
			{
				$result = $query->row();
				$display_block2 .= 'personal note: ' . $result->note;
			}
			else
			{

				$display_block2 .= "<p class='error'>Selected Contact does not have a Personal note</p>";
			}
		}


		return $display_block2;
	}

	function deleteEntry($master_id)
	{
      $this->db->where('master_id',$master_id);
	  $this->db->delete('master_name');
	  
	  if($this->db->affected_rows()>0){
		  $this->db->where('master_id',$master_id);
	  $this->db->delete(['address','telephone','email', 'fax' ,'personal_notes']);
	  }
		
	}
	
		function updateEntry($master_id)
	{
			
	$update_data=array();
		$update_data['date_modified'] = date('Y-m-d');
		$update_data['f_name'] = $this->input->post('f_name');
		$update_data['l_name'] = $this->input->post('l_name');
      $this->db->where('master_id',$master_id);
	  $this->db->update('master_name');
	  
	  if($this->db->affected_rows()>0){
		  $this->db->where('master_id',$master_id);
	  $this->db->update(['address','telephone','email', 'fax' ,'personal_notes']);
	  }
		
	}
	
    function getSelectedContactDetailsUpdate(){
		
	}

}
?>

