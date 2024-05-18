// 清空會員相關欄位
function clearMemberFields() {
  document.getElementById("memberId").value = "";
  document.getElementById("memberName").value = "";
  document.getElementById("recipientName").value = "";
  document.getElementById("recipientMobile").value = "";
  document.getElementById("city").value = "";
  document.getElementById("district").value = "";
  document.getElementById("address").value = "";
  document.getElementById("mobileInvoice").value = "";
}

// 清空收件人相關欄位
function clearRecipientFields() {
  document.getElementById("recipientName").value = "";
  document.getElementById("recipientMobile").value = "";
  document.getElementById("city").value = "";
  document.getElementById("district").value = "";
  document.getElementById("address").value = "";
}

// 清空發票相關欄位
function clearInvoiceFields() {
  document.getElementById("mobileInvoice").value = "";
  document.getElementById("taxId").value = "";
}

// disabled 收件人相關欄位
function disabledRecipientInput() {
  document.querySelectorAll(".new-address input").forEach((input) => {
    input.disabled = true;
  });
  document.querySelectorAll(".new-address select").forEach((input) => {
    input.disabled = true;
  });
}

// 取消 disabled 收件人相關欄位
function enabledRecipientInput() {
  document.querySelectorAll(".new-address input").forEach((input) => {
    input.disabled = false;
  });
  document.querySelectorAll(".new-address select").forEach((input) => {
    input.disabled = false;
  });
}

// 確認欄位是否有值
function emptyChecked(value) {
  if (value == null) return false;
  if (typeof value === "string" && value.trim() === "") return false;
  if (Array.isArray(value) && value.length === 0) return false;
  if (
    typeof value === "object" &&
    value.constructor === Object &&
    Object.keys(value).length === 0
  )
    return false;

  return true;
}

// 手機驗證欄位
function validatePhoneNumber(element) {
  const regex = /^[+-]?\d+$/;
  element.nextElementSibling.textContent = regex.test(element.value)
    ? ""
    : "請勿輸入特殊符號";
}

// 姓名欄位驗證
function validateName(element) {
  const regex = /^[a-zA-Z\u4e00-\u9fa5 ]*$/;
  element.nextElementSibling.textContent = regex.test(element.value)
    ? ""
    : "姓名只能包含中文、英文和空格";
}

// 地址欄位驗證
function validateAddress(element) {
  const regex = /^[a-zA-Z\u4e00-\u9fa5\d]*$/;
  element.nextElementSibling.textContent = regex.test(element.value)
    ? ""
    : "地址只能包含中文、英文和數字，不能有空格";
}

// 手機載具驗證
function validateMobileCarrier(element) {
  const regex = /^\/[0-9A-Z.-]{7}$/;
  element.nextElementSibling.textContent = regex.test(element.value)
    ? ""
    : "手機載具格式錯誤";
}

// 統一編號驗證
function validateTaxID(element) {
  const regex = /^\d{8}$/;
  element.nextElementSibling.textContent = regex.test(element.value)
    ? ""
    : "統一編號格式錯誤";
}

document.addEventListener("DOMContentLoaded", function () {
  const recipientNameInput = document.getElementById("recipientName");
  const recipientMobileInput = document.getElementById("recipientMobile");
  const addressInput = document.getElementById("address");
  const mobileInvoiceInput = document.getElementById("mobileInvoice");
  const taxIdInput = document.getElementById("taxId");

  recipientMobileInput.addEventListener("change", function () {
    validatePhoneNumber(this);
  });

  recipientName.addEventListener("change", function () {
    validateName(this);
  });

  addressInput.addEventListener("change", function () {
    validateAddress(this);
  });

  mobileInvoiceInput.addEventListener("change", function () {
    validateMobileCarrier(this);
  });

  taxIdInput.addEventListener("change", function () {
    validateTaxID(this);
  });
});

function toggleFormElements(disable = true) {
  // 如果使用常用地址欄位會 disabled，因此要把它取消
  setDisabled(
    ["recipientName", "recipientMobile", "district", "address"],
    false
  );
  // 只需要拋 district_id，因此 disabled city_id
  setDisabled(["city"], disable);

  // 隱藏所有 radio, select group name（不需拋到資料庫）
  document.querySelectorAll('[name^="group"]').forEach((input) => {
    input.removeAttribute("name");
  });

  // 補回去 useMemberInvoice 選項（用來傳遞 0 or 1）
  const useMemberInvoice = document.getElementById("useMemberInvoice");
  useMemberInvoice.setAttribute("name", "memberInvoice");
  useMemberInvoice.checked
    ? (useMemberInvoice.value = 1)
    : (useMemberInvoice.value = 0);

  // invoiceOptions();
}

function restoreFormState() {
  toggleFormElements(false);
}

// 快速設定 input disabled 函式
function setDisabled(ids, disabledStatus) {
  ids.forEach((id) => {
    const element = document.getElementById(id);
    if (element) {
      element.disabled = disabledStatus;
    }
  });
}

// 設定錯誤訊息
function setErrorFor(inputElement, message) {
  // 取 input 父層向下的 span helper-text
  const helperText = inputElement.parentElement.querySelector(".helper-text");
  helperText.textContent = message;

  // 如有有輸入，則隱藏內容
  inputElement.addEventListener("input", function () {
    if (this.value.trim() !== "") {
      helperText.textContent = "";
    } else {
      helperText.textContent = message;
    }
  });
}

// 清空錯誤訊息
function clearErrorFor(inputElement) {
  const helperText = inputElement.parentElement.querySelector(".helper-text");
  helperText.textContent = "";
  // helperText.style.visibility = 'hidden';
}

function submitForm() {
  const fd = new FormData(document.orderAddForm);
  fetch("api/order-add-api.php", {
    method: "POST",
    body: fd,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("新增成功");
        location.href = "order-list.php";
      } else {
        alert("新增失敗，請檢查資料是否正確。");
        console.log(data);
        // 新增失敗後，原本為了提交表單而變更的欄位狀態、value 都改回成原本的
        restoreFormState();
        toggleFormElements(false);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("發生錯誤，請稍後再試");
      restoreFormState();
      toggleFormElements(false);
    });
}

function saveToMembersOrNot() {
  const useMobileInvoice = document.getElementById("useMobileInvoice").checked;
  const saveMobileInvoice =
    document.getElementById("saveMobileInvoice").checked;
  const mobileInvoice = document.getElementById("mobileInvoice").value;
  const useTaxId = document.getElementById("useTaxId").checked;
  const saveTaxId = document.getElementById("saveTaxId").checked;
  const taxId = document.getElementById("taxId").value;

  if (useMobileInvoice && saveMobileInvoice && emptyChecked(mobileInvoice)) {
    return true;
  } else if (useTaxId && saveTaxId && emptyChecked(taxId)) {
    return true;
  } else {
    return false;
  }
}

function memberInvoiceSubmit() {
  const memberId = document.getElementById("memberId").value;
  const useMobileInvoice = document.getElementById("useMobileInvoice").checked;
  const saveMobileInvoice =
    document.getElementById("saveMobileInvoice").checked;
  const mobileInvoice = document.getElementById("mobileInvoice").value;
  const useTaxId = document.getElementById("useTaxId").checked;
  const saveTaxId = document.getElementById("saveTaxId").checked;
  const taxId = document.getElementById("taxId").value;

  if (useMobileInvoice && saveMobileInvoice && emptyChecked(mobileInvoice)) {
    const data = {
      memberId: memberId,
      mobileInvoice: mobileInvoice,
    };

    fetch("api/update-member-invoice.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => console.log(data))
      .catch((error) => console.error("Error:", error));
  }

  if (useTaxId && saveTaxId && emptyChecked(taxId)) {
    const data = {
      memberId: memberId,
      taxId: taxId,
    };

    fetch("api/update-member-invoice.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => console.log(data))
      .catch((error) => console.error("Error:", error));
  }

  return true;
}

// 錯誤驗證
function validateForm() {
  let isValid = true;
  const fieldsToCheck = [
    { id: "orderDate", message: "請選擇訂單日期" },
    { id: "memberId", message: "請輸入會員" },
    // { id: 'recipientName', message: '請輸入收件人姓名' },
    // { id: 'recipientMobile', message: '請輸入收件人手機' },
    // { id: 'city', message: '請選擇縣市' },
    // { id: 'district', message: '請選擇鄉鎮市區' },
    // { id: 'address', message: '請輸入地址' },
  ];

  fieldsToCheck.forEach((field) => {
    const inputElement = document.getElementById(field.id);
    if (inputElement.value.trim() === "" || inputElement.value === "請選擇") {
      setErrorFor(inputElement, field.message);
      isValid = false;
    } else {
      clearErrorFor(inputElement);
    }
  });

  quantityInputs = document.querySelectorAll('input[name="productQuantities[]"]');

  quantityInputs.forEach(function (input) {
    let quantity = parseInt(input.value);
    let helperText = input.parentElement.querySelector(".helper-text");

    if (quantity < 1 || isNaN(quantity)) {
      helperText.textContent = "請輸入數量";
      isValid = false;
    } else {
      helperText.textContent = "";
    }
  });

  const recipientName = document.getElementById("recipientName");
  const recipientMobile = document.getElementById("recipientMobile");
  const city = document.getElementById("city");
  const district = document.getElementById("district");
  const address = document.getElementById("address");

  if (
    !emptyChecked(recipientName.value) ||
    !emptyChecked(recipientMobile.value) ||
    !emptyChecked(city.value) ||
    !emptyChecked(district.value) ||
    !emptyChecked(address.value) ||
    district.value == "請選擇"
  ) {
    document.querySelector(".address-helper-text").textContent =
      "請輸入完整收件人資訊";
    isValid = false;
  } else {
    document.querySelector(".address-helper-text").textContent = "";
  }

  function recipientValidation() {}

  // 手機載具與統一編號
  const mobileInvoiceInput = document.getElementById("mobileInvoice");
  const taxIdInput = document.getElementById("taxId");

  if (
    document.getElementById("useMobileInvoice").checked &&
    mobileInvoiceInput.value.trim() === ""
  ) {
    setErrorFor(mobileInvoiceInput, "請輸入手機載具");
    isValid = false;
  }

  if (
    document.getElementById("useTaxId").checked &&
    taxIdInput.value.trim() === ""
  ) {
    setErrorFor(taxIdInput, "請輸入統一編號");
    isValid = false;
  }

  // 商品驗證
  if (document.querySelectorAll(".order-item").length < 1) {
    document.querySelector(".product-helper-text").textContent =
      "請選擇至少一個商品";
    isValid = false;
  }

  return isValid;
}
