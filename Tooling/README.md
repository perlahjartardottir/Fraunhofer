<a name='search-guide'>Search for POs, runs or tools</a>
-----------------------------------------
All the search functionality of the webpage is found if you click ```Tooling overview``` on the front page. You can do a basic search by adding information to the filters on the page.
There are two symbols you can use to narrow your search even more.
You can use ```%``` and ```_``` as wildcard characters.

```%``` replaces zero or more characters.</br>
```_``` replaces exactly one character.</br>
Here are a few examples where this can be useful.

- ```%rplcmt%``` searches for all POs that contain the string ```rplcmt```.
- ```%-%``` searches for all POs that contain a dash.
- ```k21506___2``` will show all runs in K2 in June 2015 that were the second run of the day.
- ```_________1``` will show first run of the day for all machines.
- ```%drill%``` will show all tools that contain the string ```drill``` in their tool ID

<a name='price-guide'>Change prices for customer</a>
====================================================
The price tables can be found by clicking the ```Home``` button.

From there click ```General Information -> Price Tables```.

Here you can click the customer name to see a price table for every customer that has at least one price linked to them.

Change specific tool price, round tool.
---------------------------
<img src="images for markdown/gif_guides/price_change/price_change_normal.gif" alt="Change price" width='400px' align='right'/>

- Select a customer and tool-size
- If a price appears you can edit it and click ```Update price``` to store it.
- If no price appears as ```Current price``` you will add it when pressing ```Update price```.

<br><br><br><br><br><br><br><br>

Change specific tool price, non round tools
---------------------------
<img src="images for markdown/gif_guides/price_change/price_change_odd.gif" alt="Change price" width='400px' align='right'/>

- Tools like Top-notch tools that do not have a diameter show up if you do not change the default setting of the diameter field.
- Same goes for tools that have a diameter/IC but no length.

<br><br><br><br><br><br><br><br>

Change all prices for a customer
---------------------------
<img src="images for markdown/gif_guides/price_change/price_change_all.gif" alt="Change price" width='400px' align='right'/>

- Select a customer and a multipler.
- Note that this is a multiplier not a percentage change.
- To increase the price by 2% you would use 1.02 as a multiplier.
- The multiplier always uses the current price table so multiplying by 1 will give you the right current price.

<br><br><br><br><br><br><br><br>

<a name='add-po-guide'>A new PO. From adding to shipping.</a>
================

The following videos show the full life span of a PO in the database, from adding it to shipping it. Right click the videos and click 'Open image in New Tab' to enlarge them, you can also zoom in on your browser.

Adding a new PO to the database
--------------------------------
<img src="images for markdown/gif_guides/make_new_po.gif" alt="New po gif" width='400px' align='right'/>

- You can also use the shortcut button ```Add PO``` in the header of the website.
- The initial inspection should be 'ok' or 'OK' if everything is good, if not write a short description of what is wrong. I.e. "missing tools" or "broken box on 5 tools rest OK".
- Number of lines is the number of different tools on this PO.
- Make sure you click ```Add PO``` before you click ```Add tools to PO```.
<br><br><br><br><br><br>
Adding a scan of the PO
-----------------------
<a href="images for markdown/gif_guides/add_scan_to_po.gif" target="blank"><img src="images for markdown/gif_guides/add_scan_to_po.gif" alt="Add scan to PO" width='400px' align='right'/></a>

- Use the scanner and the computer in the lab to save a scanned picture of the PO.
- Ask your supervisor about what to name the scan and were to save it.
- Make sure you have the right image before uploading it
- <strong>Try to keep the file-size as small as possible.</strong>
- Click the image to open a bigger version of it in another tab.
<br><br><br><br><br><br><br><br>
Adding tools to a PO
---------------------
<a href="images for markdown/gif_guides/add_tool_to_po.gif" target="blank"><img src="images for markdown/gif_guides/add_tool_to_po.gif" alt="Add tool to PO" width='400px' align='right' /></a>

- Fill out the information as it is on the PO received from the customer.
- Inserting a diameter and length generates the price for each customer.
- Having coating as DLC or checking ```Double ended``` will double the price.
- You can edit the auto generated price.
- If the tool does not show up right away in the table you can click the refresh button.
- You can't have the same ```Line on PO``` more than once on each PO but you can have multiple lines with the same ```Tool ID Number```.
- Clicking the red cross next to the tool will delete that tool from this PO.
<br>
Viewing the general overview page
----------------------------------
<a href="images for markdown/gif_guides/view_general_overview.gif" target="blank"><img src="images for markdown/gif_guides/view_general_overview.gif" alt="General overview" width='400px' align='right'/></a>

- After adding all the tools from the PO make sure all the information is correct.
- Click ```Print general information sheet``` for a printable version of this information.
- Press ```CTRL-p``` or <code>&#8984;-p</code> to print this page.
- Make sure to choose ```Landscape``` as a layout.
<br><br><br><br>
Adding a run
------------
<a href="images for markdown/gif_guides/add_run_to_tracksheet.gif" target="blank"><img src="images for markdown/gif_guides/add_run_to_tracksheet.gif" alt="Add run" width='400px' align='right'/></a>

- Make sure you have the correct PO chosen.
- After filling out the form click the plus to store the run.
- If the run does not show up right away in the table you can click the refresh button.
- The Run ID is auto generated. The format is ```Machine+Date+Run for machine```.
<br><br><br><br><br><br><br><br><br><br>
A quick way to add runs and edit them.
------------------------------------------------------------------
<a href="images for markdown/gif_guides/add_old_run_and_edit.gif" target="blank"><img src="images for markdown/gif_guides/add_old_run_and_edit.gif" alt="Add old run" width='400px' align='right'/></a>

- The recently added runs list shows the 6 newest runs in the database.
- Select the run and click ```Add run```. Now this run is linked to this PO.
- To edit information about a run click the right ```Run ID```.
- The fields in the edit pop-up display the information as it is so only edit fields that are wrong.
<br><br><br><br><br><br><br><br><br>
Assign tools to run
--------------------
<a href="images for markdown/gif_guides/add_tools_to_run.gif" target="blank"><img src="images for markdown/gif_guides/add_tools_to_run.gif" alt="Add tools to run" width='400px' align='right'/></a>

- You can add the same line item to multiple runs
- You can edit the information displayed in the table by clicking the right ```Line item #```.
- Clicking the red cross next to the table will delete that entry from the table.
- If the line item does not show up right away in the table you can click the refresh button.
<br><br><br><br><br><br><br><br><br>
Adding tools error
------------------
<a href="images for markdown/gif_guides/add_tools_to_run_error_warning.gif" target="blank"><img src="images for markdown/gif_guides/add_tools_to_run_error_warning.gif" alt="Add tool error" width='400px' align='right'/></a>

- You can not add the same line item twice to the same run.
- If you want to change the number of tools in a run, delete the entry and add it again with correct information.
- If you add more tools to runs than you received on your PO you will get a warning. This can either be re-runs or an input error.
<br><br><br><br><br><br><br><br>
Shipping the PO
----------------
<a href="images for markdown/gif_guides/packing_list.gif" target="blank"><img src="images for markdown/gif_guides/packing_list.gif" alt="New po gif" width='400px' align='right'/></a>

- After all tools have been coated you can ship the PO back.
- Make sure all information is correct. You can change the tools in shipment if it is wrong for some reason.
- Add a comment and a shipping date and click ```Save```
- <strong>The customer we are sending this shipment too will see this comment.</strong>

<br><br><br><br><br><br>

<a name='discount-guide'>Add a discount to a PO/tool</a>
=================================
The following videos show how to apply a discount to a PO/tool.

Finding the right PO
--------------------
<img src="images for markdown/gif_guides/discount/discount_navigate.gif" alt="Discount navigate" width='400px' align='right'/>

- Go to the PO search view to find your PO.
- Click your PO and choose ```Edit -> Edit PO```.

<br><br><br><br><br><br><br><br><br><br><br><br>

Applying the discount
--------------------
<img src="images for markdown/gif_guides/discount/discount_apply.gif" alt="Discount apply" width='400px' align='right'/>

- Click the tool you want to apply the discount to.
- Enter how many tools have the discount and the amount per tool.
- After you click ```Apply discount``` refresh the page.
<br><br><br><br><br><br>
