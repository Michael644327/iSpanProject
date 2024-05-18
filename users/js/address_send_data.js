const addressSendData = function (e) {
  e.preventDefault();
  // let isPass = true; // 整個表單有沒有通過檢查

  let $addressForm = $("#addressForm");
  let $row = $addressForm.find(".col-12");
  let $errorText = $addressForm.find(".form-text");
  let $address_id = $addressForm.find('[name="address_id"]');
  let $city = $addressForm.find('[name="city"]');
  let $district_id = $addressForm.find('[name="district_id"]');
  let $addressLine = $addressForm.find('[name="addressLine"]');
  let $recipient_name = $addressForm.find('[name="recipient_name"]');
  let $mobile_phone = $addressForm.find('[name="mobile_phone"]');

  let formData = {
    user_id: addressForm.user_id.value,
  };

  let formDataIsPass = true;
  let updateOrInsert = [];
  let DELETE = [];

  for (let i = 0; i < $row.length; i++) {
    $address_id.eq(i).removeData("tag");
    let $dataTag = $address_id.eq(i).data("tag");

    console.log($errorText[i]);
    $errorText[i].innerHTML = "";
    //驗證是否為空值
    let isPass = true;
    let validateField = function (field, index) {
      let isEmpty = field.value.length === 0 && $dataTag !== "delete";
      let borderColor = isEmpty ? "red" : "#CCC";
      field.style.border = `1px solid ${borderColor}`;
      if (isEmpty) {
        formDataIsPass = false;
        isPass = false;
      }
    };
    let fields = [
      $city[i],
      $district_id[i],
      $addressLine[i],
      $recipient_name[i],
      $mobile_phone[i],
    ];

    let data = {};
    if ($dataTag === "insert" || $dataTag === "update") {
      data = {
        address_id: $address_id[i].value,
        city: $city[i].value,
        district_id: $district_id[i].value,
        addressLine: $addressLine[i].value,
        recipient_name: $recipient_name[i].value,
        mobile_phone: $mobile_phone[i].value,
      };
      fields.forEach((field) => validateField(field, i));
    } else if ($dataTag === "delete") {
      data = {
        address_id: $address_id[i].value,
      };
    } else {
      return alert(`發生預期外的錯誤，請重新檢查程式碼`);
    }

    //驗證成功的話分別包裝陣列
    if (isPass) {
      if ($dataTag === "update" || $dataTag === "insert")
        updateOrInsert.push(data);
      if ($dataTag === "delete") DELETE.push(data);
    } else {
      $errorText[i].innerHTML = "請填寫完整地址";
    }
  }

  formData.delete = DELETE;
  formData.updateOrInsert = updateOrInsert;

  // console.log(formDataIsPass);
  let failureInfo = document.querySelector("#addressForm .alert");

  if (formDataIsPass) {
    console.log(formData);

    let url = `api/address_send_data_api.php`;

    fetchJsonData(url, formData)
      .then((data) => {
        let failureInfo = document.querySelector('#successModal .alert');
        failureInfo.innerHTML = '';

        if (data.success_delete || data.success_update) {
          failureInfo.classList.remove('alert-danger');
          failureInfo.classList.add('alert-success');
          failureInfo.innerHTML = '資料修改成功';
        } else {
          failureInfo.classList.remove('alert-success');
          failureInfo.classList.add('alert-danger');
          failureInfo.innerHTML = data.error;
        }
        successModal.show();

        setTimeout(function () {
          successModal.hide();
          addressModal.hide();
          editModal.show();
          fetchEditModalData(addressForm.user_id.value);
        }, 1000);
      })
      .catch((error) => console.error("Error:", error));
  } else {
    return;
  }
};
