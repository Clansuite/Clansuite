<?php
namespace Koch\Module;

interface AdminModuleInterface
{
    public function action_admin_list();     # GET     /foos
    public function action_admin_show();     # GET     /foos/:foo_id
    public function action_admin_new();      # GET     /foos/new
    public function action_admin_edit();     # GET     /foos/:foo_id/edit
    public function action_admin_insert();   # POST    /foos
    public function action_admin_update();   # PUT     /foos/:foo_id
    public function action_admin_delete();   # DELETE  /foos/:foo_id
}
?>