<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Adjustment Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during adjustment for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    //==========================================
    // Adjustment module messages
    //==========================================
    'title' => 'Adjustment',
    'Adjustments' => 'Adjustments',
    'Create_Adjustment' => 'Create Adjustment',
    'Edit_Adjustment' => 'Edit Adjustment',
    'Adjustment_Details' => 'Adjustment Details',
    'Adjustment_List' => 'Adjustment List',
    'sure' => 'Are you sure?',

    //==========================================
    // Error handling messages
    //==========================================
    'error_creating' => 'Sorry, there a problem while creating adjustment.',
    'success_creating' => 'Success, your adjustment have been created.',
    'error_updating' => 'Sorry, there\'re a problem while updating adjustment.',
    'success_updating' => 'Success, your adjustment have been updated.',
    'error_deleting' => 'Sorry, there\'re a problem while deleting adjustment.',
    'success_deleting' => 'Success, your adjustment have been deleted.',

    //==========================================
    // Adjustment table messages
    //==========================================
    'ID' => 'ID',
    'Product' => 'Product',
    'Type' => 'Type',
    'Quantity' => 'Quantity',
    'Reason' => 'Reason',
    'Adjusted_By' => 'Adjusted By',
    'Adjusted_At' => 'Adjusted At',
    'Select_Product' => 'Select Product',
    'Select_Type' => 'Select Type',
    'Increase' => 'Increase',
    'Decrease' => 'Decrease',
    'Enter_reason' => 'Enter reason for adjustment...',
    'No_reason_provided' => 'No reason provided',

    //==========================================
    // Filter and Search messages
    //==========================================
    'Search_Adjustments' => 'Search Adjustments',
    'Filter_Type' => 'Filter Type',
    'Filter_Date_From' => 'Date From',
    'Filter_Date_To' => 'Date To',
    'All_Types' => 'All Types',
    'Search_placeholder' => 'Search by product name, reason, or user...',

    //==========================================
    // Action messages
    //==========================================
    'Actions' => 'Actions',
    'really_delete' => 'Do you really want to delete this adjustment?',
    'delete_confirmation' => 'You are about to delete :type adjustment of :quantity units for :product',
    'yes_delete' => 'Yes, delete it!',
    'No' => 'No',
    'Yes' => 'Yes',
    'deleted' => 'Delete Success',
    'deleted_message' => 'Adjustment Has Been Deleted',

    //==========================================
    // Empty state messages
    //==========================================
    'No_Adjustments_Found' => 'No Adjustments Found',
    'Empty_State_Description' => 'Get started by creating your first stock adjustment.',

    //==========================================
    // Validation messages
    //==========================================
    'Please_add_at_least_one_item' => 'Please add at least one item to the adjustment.',
    'product_id_required' => 'Product is required',
    'type_required' => 'Type is required',
    'type_in' => 'Type must be either increase or decrease',
    'quantity_required' => 'Quantity is required',
    'quantity_min' => 'Quantity must be at least 1',
    'adjusted_at_required' => 'Adjusted date is required',
    'adjusted_at_date' => 'Adjusted date must be a valid date',
    'Quantity_Hint' => 'Enter the quantity to adjust. For decrease, quantity cannot exceed current stock.',
    'Current_Stock' => 'Current Stock',
    'Additional_Information' => 'Additional Information',

    //==========================================
    // Stock update messages
    //==========================================
    'stock_increased' => 'Stock increased by :quantity',
    'stock_decreased' => 'Stock decreased by :quantity',
    'stock_updated' => 'Stock updated successfully',
];