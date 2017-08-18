<?php

$selBlock = \app\Helper\Database\messStore::genLinks('selBlock', __ROOT__.'/app/Store/tsDOMText', true);
$tableHeader = \app\Helper\Database\messStore::genLinks('trackerTableHeader', __ROOT__.'/app/Store/tsDOMText', true);

?>
<div class="content_start">
    <section class = 'chooseSection'>
      <section id = "projectSelecter">
          <div class="customerBlock">
              <span class = 'labelStyle'><?php echo $selBlock->cust; ?></span>
              <select class = 'customer'  id = "customerID" onchange="$('#projectID').getPjName(this.value);" name = 'customer'>
              </select>
          </div>
          <div class="pjNameBlock">
              <span class = 'labelStyle'><?php echo $selBlock->projname; ?></span>
              <select class = 'projectName' id = "projectID" name = 'projectName'>
              </select>
          </div>
      </section>
      <section id="objectBlock">
          <div class="objectProgName">
              <div class="objectType">
                  <span class = 'labelStyle'><?php echo $selBlock->oType; ?></span>
                  <select id = "objectType" name = 'oType'>

                  </select>
              </div>
              <div class="programerName">
                  <span class = 'labelStyle'><?php echo $selBlock->pname; ?></span>
                  <select id = "programmerName" name = 'pName'>
                  </select>
              </div>
          </div>
          <div class="objectfilter">
              <!-- <div class="objectName">
                  <span class = 'labelStyle'><?php// echo $selBlock->oName; ?></span>
                  <select type="text" name="objName" id = "objName">

                  </select>
              </div>
              <div class="objectTitle">
                  <span class = 'labelStyle'><?php// echo $selBlock->oTitle; ?></span>
                  <input type="text" name="objTitle" id = "objTitle">
              </div> -->
              <div class="objectButton">
                  <button id = "show" class = 'btn btn-success'>Show</button>
              </div>
          </div>
      </section>
    </section>

    <table class="table table-hover" id = "tableTracker">
        <thead>
          <tr style="font-weight: bold;">
            <th></th>
            <th style = 'text-align: center;' attr = "userName"><?php echo $tableHeader->ND; ?></th>
            <th attr = "account"><?php echo $tableHeader->DEF; ?></th>
            <th style = "width: 30%;" attr = "descr"><?php echo $tableHeader->RFQ; ?></th>
            <th attr = "jobType"><?php echo $tableHeader->QCI; ?></th>
            <th attr = "dateJob"><?php echo $tableHeader->QCD; ?></th>
            <th style = 'text-align: center;' attr = "hoursJob"><?php echo $tableHeader->QCNR; ?></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
</div>
