<h2>Search Results</h2>

<table cellpadding="0" cellspacing="0" id="searchResults">
	<tr>
    	<th>JobNo</th>
        <th>DocType</th>
        <th>Customer</th>
        <th>File Number</th>
        <th>Control Number</th>
        <th>Name</th>
    </th>

<? foreach($search_results->result() as $row): ?>
	<tr>
    	<td><a href="/legal/entry/<?=$row->id?>"><?=$row->id?></a>&nbsp;</td>
    	<td><?=$docType[$row->legal_doctype_id]?>&nbsp;</td>
    	<td><?=@$custList[$row->legal_customer_id]?>&nbsp;</td>
    	<td><?=$row->filenumber?>&nbsp;</td>
    	<td><?=$row->controlnumber?>&nbsp;</td>
    	<td><?=$row->name?>&nbsp;</td>
    </tr>
<? endforeach; ?>
</table>


<input type="button" name="returnHome" id="returnHome" value="Return to Main Menu" onclick="window.location = '/';" class="button" />