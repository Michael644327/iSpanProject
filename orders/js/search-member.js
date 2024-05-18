// 建立暫存會員姓名、手機的變數
let fetchMemberName = '';
let fetchMemberPhone = '';
let fetchMemberMobileInvoice = '';
let fetchMemberTaxId = '';


// 會員搜尋功能
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll(".search-member").forEach((input) => {
    // 會員選單
    let resultsContainer = input.nextElementSibling;
    // 會員編號 help text
    let resultsHelp = resultsContainer.nextElementSibling;
    let debounceTimer;
    
    
    // 監聽會員編號輸入
    input.addEventListener("input", function () {
      // 初始化定時器、會員編號 help text
      clearTimeout(debounceTimer);
      document.getElementById('memberName').value = '';
      clearRecipientFields();
      clearInvoiceFields();
      resultsHelp.innerText = '';

      // 如果輸入框無內容，隱藏選單、清空會員相關欄位
      if (this.value.length < 1) {
        resultsContainer.innerHTML = "";
        resultsContainer.classList.remove("show");
        resultsHelp.innerText = '查無會資料';
        clearMemberFields();
        return;
      }

      debounceTimer = setTimeout(() => {
        fetch("api/search-member-api.php?query=" + encodeURIComponent(this.value))
          .then((response) => response.json())
          .then((data) => {
            resultsContainer.innerHTML = "";
            if (data.length) {
              data.forEach((item) => {
                let option = document.createElement("a");

                // 如果會員狀態為啟用 1，建立會員選項
                if (item.user_status == 1) {
                  option.className = "dropdown-item";
                  option.href = "#";
                  const displayName = item.name ? `(${item.user_id}) ${item.name}` : `(${item.user_id}) 無紀錄會員姓名`;
                  option.textContent = displayName;
                  resultsContainer.appendChild(option);
                }

                // 點擊選項後將資料帶入到 memberId, memberName 當中
                option.addEventListener("click", function () {
                  document.getElementById("memberId").value = item.user_id;
                  document.getElementById("memberName").value = item.name || "無紀錄會員姓名";
                  document.getElementById('useMemberInvoice').checked = false;
                  document.getElementById('useMobileInvoice').checked = false;
                  document.getElementById('useTaxId').checked = false;
                  resultsContainer.classList.remove("show");

                  // 儲存會員姓名、手機至變數裡
                  fetchMemberName = item.name;
                  fetchMemberPhone = item.mobile_phone;
                  fetchMemberMobileInvoice = item.invoice_carrier_id;
                  fetchMemberTaxId = item.tax_id;

                  // 若有勾選「帶入會員資料」，會連動變更收件人與收件手機欄位內容
                  if (document.getElementById("useMemberInfo").checked == true) {
                    document.getElementById("recipientName").value = item.name;
                    document.getElementById("recipientMobile").value = item.mobile_phone || '';
                  }
                  

                  if (item.invoice_carrier_id) {
                    document.getElementById('useMobileInvoice').checked = true;
                    document.getElementById('mobileInvoice').value = item.invoice_carrier_id;
                    document.getElementById('mobileInvoice').nextElementSibling.textContent = '';
                  }
                  if (item.tax_id) {
                    document.getElementById('taxId').value = item.tax_id;
                    document.getElementById('taxId').nextElementSibling.textContent = '';
                  }
                  if (!item.invoice_carrier_id && item.tax_id) {
                    document.getElementById('useTaxId').checked = true;
                  }
                  if(!item.tax_id && !item.invoice_carrier_id) {
                    document.getElementById('useMemberInvoice').checked = true;
                  }
                                  
                  toggleInvoiceType();

                  return false;
                });
              });
              resultsContainer.classList.add("show");
            } else {
              // 如果搜尋結果為空，隱藏選單、顯示搜尋結果提示
              resultsContainer.classList.remove("show");
              resultsHelp.innerText = '查無會員資料';
              clearMemberFields();
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            clearMemberFields();
          });
      }, 400);
      // fetch 結束
    });
    // 監聽會員編號輸入結束

  });

  
});

