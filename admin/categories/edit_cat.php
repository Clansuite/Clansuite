<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

require '../../shared/prepend.php';

$cat_modulname = (string) get('cat_modulname');

//SELECT * FROM `category` WHERE 
//`cat_id`, `cat_modulname`, `cat_sortorder`, `cat_name`, 
//`cat_image_url`, `cat_description`

$catlist = $Db->getAll("SELECT *	FROM category WHERE cat_modulname = ? 
 								ORDER BY cat_sortorder DESC", $cat_modulname);

?>

<?php $TITLE = 'Edit Category: '.$cat_modulname; ?>
<?php include '../shared/header.tpl'; ?>

    <h1><?php echo $TITLE ?></h1>
    
    <p><input type="button" class="button" value="Add" onclick="location='add_cat.php?cat_modulname=<?php echo $cat_modulname; ?>'">
    <?php echo 'Items: ('. count($catlist) .')'; ?>
    </p>

    <table class="listing">
    <tr>
        <th>#</th>
        <th>cat_modulname</th>
        <th>cat_sortorder</th>
        <th>cat_name</th>
        <th>cat_image_url</th>
        <th>cat_description</th>
        <th>Action</th>
    </tr>

    <?php $temp_cat_modulename = ""; // temp-var for cat-modulname
    foreach ($catlist as $cat): ?>
    <tr>
        <td><?php echo $cat['cat_id']; ?></td>
        <td><?php echo $cat['cat_modulname']; ?></td>
        <td><?php echo $cat['cat_sortorder']; ?></td>
        <td><?php echo $cat['cat_name']; ?></td>        
        <td><?php echo $cat['cat_image_url']; ?></td>
        <td><?php echo $cat['cat_description']; ?></td>
        <td>
        <?php 
        // checks temp-var-cat for a new cat_modulname
        // prints edit button only for first row with new cat_modulename
        // finally sets  temp-var to cat_modulname
        if ($temp_cat_modulename != $cat['cat_modulname']) { 
        ?> 
        
        <input type="button" class="button" value="Edit" 
        onclick="location='edit_cat.php?cat_modulname=<?php echo $cat['cat_modulname']; ?>'"> 
        
        <?php $temp_cat_modulename = $cat['cat_modulname'];
        }; //close if
        ?>
        
        </td>    
    </tr>
        
    <?php endforeach; ?>

    </table>
    

    <script type="text/javascript" src="../shared/listing.js"></script>

<?php include '../shared/footer.tpl'; ?>
<?php echo "<pre>",print_r($catlist),"</pre>"; ?>