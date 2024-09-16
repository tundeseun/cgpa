<?php
/* Class name: Form
* Description: A class that creates a simple HTML form
* containing only text input fields. The
* class has 3 methods.
*/
class Form
{
	var $fields=array(); # contains field names and labels
	var $processor; # name of program to process form
	var $submit ="Submit Form"; # value for the submit button
	var $Nfields = 0; # number of fields added to the form
	var $post = array(); # value array
	var $ID;
	var $nav = array();
	var $index=0;
	var $statusMsg="Custom Messages";
	/* Constructor: User passes in the name of the script where
	* form data is to be sent ($processor) and the value to show
	* on the submit button.
	*/
	function __construct($processor,$submit)
	{
		$this->processor = $processor;
		$this->submit = $submit;
		$this->ID=0;
		
	}
	function cboSelect($query,$name,$label="na")
	{
		echo "<tr><td align=\"right\">{$label}: </td>\n";
		echo "<td>";	
		// build selection list for the day 
		$result = fetch_records($query);
		echo "<select name='$name'>\n";
		while ($line = mysqli_fetch_array($result, mysqli_ASSOC)) {
			$key=array_keys($line);
			echo "<option value=". $line[$key[0]];
			
			if ($_POST[$name]==$line[$key[0]])
			{
				echo " selected";
			}
			echo  " > " .$line[$key[1]] ."\n";
		}
		echo "</select>\n";
		echo "</td></tr>\n";
	}
	function beginForm()
	{
		echo "<form action='{$this->processor}' method='post'>";
		echo "<table width='100%'>";
	}
	/* Display form function. Displays the form.
	*/
	function displayForm()
	{
		for($j=1;$j<=sizeof($this->fields);$j++)
		{
			$n=$this->fields[$j-1]['name'];
			echo "<tr><td align=\"right\">{$this->fields[$j-1]['label']}: </td>\n";
			echo "<td><input type='text' name='{$this->fields[$j-1]['name']}' value='{$this->post[$n]}'></td></tr>\n";
		}		
		echo "<tr><td colspan=2 align='center'><input type='hidden' name='ID' value='{$this->ID}' /><input type='submit' name='Submit' value='{$this->submit}'><input type='reset'value='Reset'> </td></tr>\n";
		
		echo "</table>";
		echo "{$this->navigation($this->statusMsg)}";
		echo "</form>";
		
	}
	/* Function that adds a field to the form. The user needs to
	* send the name of the field and a label to be displayed.
	*/
	function addNew()
	{
		$_POST['ID']='0';
		foreach ($this->post as $i => $value) {
    		unset($this->post[$i]);
		}
	}
	function deleteRec($table)
	{
		
		$id=$_GET['r'];
		$_GET['r'] +=1;
		$id=$this->nav[$id];
		$query= "Delete  from ".$table." Where id= ".$id;
		//echo "$query <br>";
		$result = fetch_records($query);
		$this->moveRec('next',$table);
	}
	function moveRec($move,$table)
	{
		$id=$_GET['r'];
		switch ($move)
		{
			case 'first':
				$id=0;
				$mode=$this->nav[$id];
				
			 break;
			 case 'prev':
				$id =$id-1;
				if ($id<0 ){$id=0;}
				$mode=$this->nav[$id];
			 break;
			 case 'next':
				$id = $id + 1;
				if ($id>sizeof($this->nav)-1){$id=sizeof($this->nav)-1;}
				$mode=$this->nav[$id];
			 break;
			 case 'last':
			 	$id=sizeof($this->nav)-1;
				$mode=end($this->nav);
			 break;
			default:
		}
		$query= "select * from ".$table." Where id= ".$mode." order by id ";
		$this->index=$id;
		$this->ID=$mode;
		$id +=1;
		$this->statusMsg= 'Record '. $id  .' of '. sizeof($this->nav);
	//	echo "$query <br>";
		$result = fetch_records($query);
		
		while ($line = mysqli_fetch_array($result, mysqli_ASSOC)) {
			foreach ($line as  $varname => $varvalue) {
				$this->post[$varname]=$varvalue;
			}
		}	
	}
	function addField($name,$label)
	{
		$this->fields[$this->Nfields]['name'] = $name;
		$this->fields[$this->Nfields]['label'] = $label;
		$this->Nfields = $this->Nfields + 1;
	}
	function navigation($msg='Custom messages here ')
	{
		echo '<table border="0"><tr>';
		echo '<td><a href='.$this->processor.'?nav=new&r='.$this->index.'><img src="img/add.png" alt="Add New" width="16" height="16"  /></a></td>';
		echo '<td><a href='.$this->processor.'?nav=first&r='.$this->index.'><img src="img/resultset_first.png" alt="First" width="16" height="16"/></a></td>';
		echo '<td><a href='.$this->processor.'?nav=prev&r='.$this->index.'><img src="img/resultset_previous.png" alt="Previous" width="16" height="16" /></a></td>';
		echo "<td>$msg</td>";
		echo '<td><a href='.$this->processor.'?nav=next&r='.$this->index.'><img src="img/resultset_next.png" alt="Next" width="16" height="16"  /></a></td>';
		echo '<td><a href='.$this->processor.'?nav=last&r='.$this->index.'><img src="img/resultset_last.png" alt="Last" width="16" height="16"  /></a></td>';
		echo '<td><a href='.$this->processor.'?nav=del&r='.$this->index.'><img src="img/cut_red.png" alt="Delete" width="16" height="16" /></a></td>';
		echo '</tr></table>';
	}
	function loadID($table)
	{
		$query= "select ID from ".$table." order by id ";	
		$result = fetch_records($query);
		while ($line = mysqli_fetch_array($result, mysqli_ASSOC)) {
			foreach ($line as  $varname => $varvalue) {
				$this->nav[]=$varvalue;
			}
		}
		
	}
	function regNo($table)
	{
		$rgN=$this->ID + 200000;
		$query ="update ".$table." set reg_no ='$rgN' where id=".$this->ID ;
					
					//echo "$query";
		$result = fetch_records($query);
		$this->statusMsg='Record was added!';
	
	}
	function saveData($table) 
	{
		foreach ($_POST as $varname => $varvalue) 
		{
			switch ($varname)
			{
				case 'Submit':
				 break;
				 case 'Go':
				 break;
				 case 'ID':
				 break;
				 case 'find':
				 break;
				case 'txtfind':
				 break;
				case 'MAX_FILE_SIZE':
				 break;
				case 'userfile':
				 break;
				default:

				if ($varvalue ==''){$varvalue=' ';}
				$this->post[$varname]=htmlentities($varvalue);
				if ($fld==''){$fld .= $varname;} else {$fld .= ",".$varname;}
				if ($update==''){$update .= $varname." = '".$this->post[$varname]."'";} else {$update .= ",".$varname." = '".$this->post[$varname]."'";}
			}
		}
		if (isset($_POST['Submit']))
		{
			if ($_POST['ID']=='0')
			{
				$query= "insert into ".$table." ($fld) values ('" . implode("','", $this->post)."')";	
				//echo "$query <br>" ;
				$result = fetch_records($query);
				$this->ID =  mysqli_insert_id();
				$this->index=sizeof($this->nav)+1;
				
				$query ="update ".$table." set id ='".$this->ID."' where id=".$this->ID ;
				
				//echo "$query";
				$result = fetch_records($query);
				$this->statusMsg='Record was added!';
			}else{	
				$this->ID=$_POST['ID'];
				$query ="update ".$table." set ".$update ." where id=".$this->ID;
				//					echo "$query";
				$result = fetch_records($query);
				$this->statusMsg='Record was modified!';
			}
		}	
		
	}
}
?>