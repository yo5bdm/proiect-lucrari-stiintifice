<div class="row">
    <div class="col-md-3">&nbsp;</div>
    <div class="col-md-6 text-center">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="card-title text-center">Înregistrare</h3>
            </div>
            <div class="panel-body">
                <form action="" method="POST">
                    <?php $form = new Form(new User());
                    echo $form->setAllRequired(true)
                            ->dontPrintArrays()
                            ->excludeField('group')
                            ->printPostValues($this->data)
                            ->generate();
                    ?>
                    <p><input type="submit" class="form-control btn btn-success" value="Salveaza"/></p>
                </form>
            </div>
        </div>
    </div>
</div>