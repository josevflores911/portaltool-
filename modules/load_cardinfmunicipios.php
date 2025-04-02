<?php
/*
descritivo da tela de carregamento de informações do municipio
<div class="row row-cols-sm-auto gx-3 align-items-center justify-content-around">
    <div class="col p-0">
        <div class="input-group">
            <span class="input-group-text p-0">
                <label for="municipio" class="form-label text-black mb-0">Município</label>
            </span>
            <input id="municipio" type="text" class="form-control form-control-sm px-2" 
                    value="<?= htmlspecialchars($data['dados_municipio'][0]['nm_muni']) ?>" readonly>
        </div>
    </div>
    <div class="col-1 p-0">
        <div class="input-group">
            <span class="input-group-text p-0">
                <label for="dt-vencimento" class="form-label text-black mb-0">Data Vencimento</label>
            </span>
            <input id="dt-vencimento" type="text" class="form-control form-control-sm" 
                    value="<?= htmlspecialchars($data['dados_municipio'][0]['dt_vencrecolhimento']) ?>" readonly>
        </div>
    </div>
    <div class="col-1 p-0">
        <div class="input-group">
            <span class="input-group-text p-0">
                <label for="info-contato" class="form-label text-black mb-0">Forma de Contato</label>
            </span>
            <input id="info-contato" type="text" class="form-control form-control-sm">
        </div>
    </div>
    <div class="col p-0">
        <h6 id="agencias" class="form-control-plaintext p-0 text-white">
            Agências: <?= count($data['list_agencias']) ?>
        </h6>
    </div>
    <div class="col p-0">
        <h6 id="mes-compet" class="form-control-plaintext p-0 text-white">
            Competência: <?= htmlspecialchars($data['dt_compet']) ?>
        </h6>
    </div>
    <div class="col-1 p-0 d-flex align-items-center">
        <label for="tp-recolh" class="form-check-inline col">Unificado</label>
        <input id="tp-recolh" class="form-check-input col-1" type="checkbox">
    </div>
</div>
*/
?>