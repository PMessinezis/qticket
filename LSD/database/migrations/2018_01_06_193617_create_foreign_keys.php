<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('source_id')->references('id')->on('sources')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('requestedBy_id')->references('id')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('onBehalfOf_id')->references('id')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('subcategory_id')->references('id')->on('subcategories')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('status_id')->references('id')->on('statuses')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('parentTicket_id')->references('id')->on('tickets')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('assignedGroup_id')->references('id')->on('groups')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('assignedResolver_id')->references('id')->on('resolvers')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('assignedVendor_id')->references('id')->on('tickets')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('rootCause_id')->references('id')->on('rootCauses')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('categories', function(Blueprint $table) {
			$table->foreign('type_id')->references('id')->on('types')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('categories', function(Blueprint $table) {
			$table->foreign('defaultGroup_id')->references('id')->on('groups')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('resolvers', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('groups', function(Blueprint $table) {
			$table->foreign('department_id')->references('id')->on('departments')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('group_resolver', function(Blueprint $table) {
			$table->foreign('group_id')->references('id')->on('groups')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('group_resolver', function(Blueprint $table) {
			$table->foreign('resolver_id')->references('id')->on('resolvers')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('subcategories', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('categories')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->foreign('ticket_id')->references('id')->on('tickets')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->foreign('fromStatus_id')->references('id')->on('statuses')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->foreign('toStatus_id')->references('id')->on('statuses')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->foreign('updatedBy_id')->references('id')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('attachments', function(Blueprint $table) {
			$table->foreign('uploadedByUser_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_source_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_requestedBy_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_onBehalfOf_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_category_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_subcategory_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_status_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_parentTicket_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_assignedGroup_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_assignedResolver_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_assignedVendor_id_foreign');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->dropForeign('tickets_rootCause_id_foreign');
		});
		Schema::table('categories', function(Blueprint $table) {
			$table->dropForeign('categories_type_id_foreign');
		});
		Schema::table('categories', function(Blueprint $table) {
			$table->dropForeign('categories_defaultGroup_id_foreign');
		});
		Schema::table('resolvers', function(Blueprint $table) {
			$table->dropForeign('resolvers_user_id_foreign');
		});
		Schema::table('groups', function(Blueprint $table) {
			$table->dropForeign('groups_department_id_foreign');
		});
		Schema::table('group_resolver', function(Blueprint $table) {
			$table->dropForeign('group_resolver_group_id_foreign');
		});
		Schema::table('group_resolver', function(Blueprint $table) {
			$table->dropForeign('group_resolver_resolver_id_foreign');
		});
		Schema::table('subcategories', function(Blueprint $table) {
			$table->dropForeign('subcategories_category_id_foreign');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->dropForeign('ticketUpdates_ticket_id_foreign');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->dropForeign('ticketUpdates_fromStatus_id_foreign');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->dropForeign('ticketUpdates_toStatus_id_foreign');
		});
		Schema::table('ticketUpdates', function(Blueprint $table) {
			$table->dropForeign('ticketUpdates_updatedBy_id_foreign');
		});
		Schema::table('attachments', function(Blueprint $table) {
			$table->dropForeign('attachments_uploadedByUser_id_foreign');
		});
	}
}