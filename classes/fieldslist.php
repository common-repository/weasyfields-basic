<?php 

namespace WeasyFields;

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ){
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class FieldsList extends \WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_row = count( $data );

        $this->set_pagination_args(
            array(
                'total_items' => $total_row,
                'per_page'    => $per_page
            )
        );

        $data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

        $this->_column_headers = array( $columns, array(), $sortable );
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return array
     */
    public function get_columns()
    {
        $columns = array(
            'fields_title'        => esc_html__( 'Fields title', 'weasyfields' ),
            'fields_type'         => esc_html__( 'Fields type', 'weasyfields' ),
            'fields_status'       => esc_html__( 'Fields status', 'weasyfields' ),
            'fields_created_date' => esc_html__( 'Fields created data', 'weasyfields' ),
            'fields_edit'         => esc_html__( 'Fields edit', 'weasyfields' )
        );

        return $columns;
    }

    public function get_sortable_columns()
    {
        return array(
            'fields_created_date' => array( 'fields_created_date', false )
        );
    }

    /**
     * Get the table data
     *
     * @return array
     */
    private function table_data()
    {
        $url = admin_url( 'admin.php?page=weasyfields-fields' );

        $order_by = isset( $_GET['orderby'] ) ? strval( $_GET['orderby'] ) : "fields_created_date";
        $order = isset( $_GET['order'] ) ? strval( $_GET['order'] ) : "DESC";

        global $wpdb;
        $table = $wpdb->prefix . "weasyfields";

        $query = $wpdb->prepare( "SELECT * FROM `{$table}` ORDER BY `%1s` %2s", array( $order_by, $order ) );
        $fields_list = $wpdb->get_results( $query );

        $data = array();
            
        foreach ( $fields_list as $fields ) :
                    
            $data[] = array(
                'fields_title'        => esc_html( $fields->fields_title ),
                'fields_type'         => esc_html( $fields->fields_type ),
                'fields_status'       => esc_html( $fields->fields_status ),
                'fields_created_date' => esc_html( $fields->fields_created_date ),
                'fields_edit'         => '<a href="'.esc_url( $url ).'&action=edit&id='.$fields->fields_id.'">'.esc_html__( 'Edit', 'weasyfields' ).'</a>'
            );

        endforeach;

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  array $item Data
     * @param  string $column_name - Current column name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case'fields_title':
            case'fields_type':
            case'fields_status':
            case'fields_created_date':
            case'fields_edit':
                return $item[$column_name];

            default:
                return print_r( $item, true ) ;
        }
    }
}