<?php

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `response_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<div class="content px-2 py-5 bg-gradient-primary">
    <h4 class="my-3"><b>Información de Respuesta</b></h4>
</div>
<div class="row mt-n5 justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <legend>Respuesta</legend>
                    <div class="pl-3"><?= isset($response) ? $response : '' ?></div>
                    <fieldset>
                        <legend>Palabras Clave</legend>
                        <ul class="list-group ml-3">
                            <?php if (isset($id)) : ?>
                                <?php
                                $kw_qry = $conn->query("SELECT * FROM `keyword_list` where response_id = '{$id}'");
                                while ($row = $kw_qry->fetch_assoc()) :
                                ?>
                                    <li class="list-group-item rounded-0"><?= $row['keyword'] ?></li>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </ul>
                    </fieldset>
                    <fieldset>
                        <legend>Sugerencias</legend>
                        <ul class="list-group ml-3">
                            <?php if (isset($id)) : ?>
                                <?php
                                $sg_qry = $conn->query("SELECT * FROM `suggestion_list` where response_id = '{$id}'");
                                while ($row = $sg_qry->fetch_assoc()) :
                                ?>
                                    <li class="list-group-item rounded-0"><?= $row['suggestion'] ?></li>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="card-footer py-1 text-center">
                <a class="btn btn-sm btn-primary bg-gradient-primary rounded-0" href="./?page=responses/manage_response&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Editar</a>
                <a class="btn btn-sm btn-light bg-gradient-light border rounded-0" href="./?page=responses"><i class="fa fa-angle-left"></i> Volver</a>
            </div>
        </div>
    </div>
</div>