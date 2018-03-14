<div class="row">
    <div class="col-xs-12">
        <h1>Lista de utilizatori</h1>
        <div><?=Helpers::table($this->data['users'],array(
            'username',
            'name',
            'group'
        ))?></div>
    </div>
</div>