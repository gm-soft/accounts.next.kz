<form class="form-horizontal" id="form" method="post" action="<?= $formAction ?>">

    <input type="hidden" name="actionPerformed" value="dataInput">
    <fieldset>
        <legend>Центр (объект) в системе</legend>

        <?php
        if (isset($formData)){
            ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="centerId">ID</label>
                <div class="col-sm-10">
                    <input type="number" id="centerId" class="form-control" required maxlength="50" value="<?= $formData["center_id"] ?>" disabled>
                </div>
                <input type="hidden" name="id" value="<?= $formData["center_id"] ?>">
            </div>
            <?php
        }
        ?>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="centerName">Название центра</label>
            <div class="col-sm-10">
                <input type="text" id="centerName" name="centerName" class="form-control" required placeholder="Название центра (50)" maxlength="50" value="<?= $formData["center_name"] ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="centerCode">Индивидуальный код центра</label>
            <div class="col-sm-10">
                <input type="text" id="centerCode" name="centerCode" class="form-control" required placeholder="Введите код центра (50)" maxlength="50" value="<?= $formData["center_code"] ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="centerLimit">Лимит центра</label>
            <div class="col-sm-10">
                <input type="number" min="0" step="1" id="centerLimit" name="centerLimit" class="form-control" required placeholder="Введите лимит центра" value="<?= $formData["center_limit"] ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="accountCount">Кол-во взятых аккаунтов</label>
            <div class="col-sm-10">
                <input type="number" min="0" step="1" id="accountCount" name="accountCount" class="form-control" placeholder="Кол-во взятых аккаунтов" value="<?= $formData["center_count"] ?>">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="centerDescription">Описание</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="centerDescription"><?= $formData["center_description"] ?></textarea>
            </div>
        </div>

    </fieldset>

    <div class="form-group row">
        <div class="col-sm-12">
            <div class="float-sm-right">
                <button type="submit" id="submit-btn" class="btn btn-primary">Сохранить</button>
                <a href="../accounts/" class="btn btn-secondary">Отмена</a>
            </div>
        </div>
    </div>

</form>

<script type="text/javascript">


    $('#form').submit(function(){
        $("#submit-btn").prop('disabled',true);
    });
</script>