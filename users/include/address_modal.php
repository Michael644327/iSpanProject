<!-- address modal start -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable w-50">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addressLabel">編輯地址</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form name="addressForm" id="addressForm">
          <!-- user_id -->
          <input type="hidden" name="user_id">
          <div class="row align-items-center">


          </div>
          <div class="col mt-3">
            <div class="row align-items-center">
              <div class="col-6 d-flex justify-content-start">
                <button type="button" class="btn btn-warning me-3" onclick="addAddressLine()">新增</button>
                <button type="button" class="btn btn-primary" onclick="quickAddAddress()">快速新增</button>
              </div>
              <div class="col-6 d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-3" data-bs-target="#editModal" data-bs-toggle="modal">關閉</button>
                <button type="submit" class="btn btn-primary" onclick="addressSendData(event)">送出</button>
              </div>
            </div>
          </div>
        </form>


      </div>
    </div>
  </div>
</div>
<!-- address modal end -->
