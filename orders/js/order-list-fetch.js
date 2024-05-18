document.addEventListener("DOMContentLoaded", function () {
  document.title = "訂單管理";
  

  // 取得 url ?page=
  const currentPageParam = new URLSearchParams(window.location.search).get("page") || 1;
  const currentPage = parseInt(currentPageParam) || 1;
  fetchOrders(currentPage);


  document.getElementById('executeSearch').addEventListener('click', function() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const memberSearch = document.getElementById('memberSearch').value;
    const productSearch = document.getElementById('productSearch').value;
    const orderStatusElement = document.getElementById('orderStatus'); // 獲取 select 元素
    const orderStatus = orderStatusElement.value;
    fetchOrders(1, startDate, endDate, memberSearch, productSearch, orderStatus !== '請選擇' ? orderStatus : '');
  });

  document.getElementById('resetSearch').addEventListener('click', function() {
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('memberSearch').value = '';
    document.getElementById('productSearch').value = '';
    document.getElementById('orderStatus').selectedIndex = 0;
    fetchOrders(1); // 重置後重新載入資料
  });


  function fetchOrders(page, startDate = '', endDate = '', memberSearch = '', productSearch = '', orderStatus = '') {
    const url = `api/order-list-api.php?page=${page}&startDate=${encodeURIComponent(startDate)}&endDate=${encodeURIComponent(endDate)}&memberSearch=${encodeURIComponent(memberSearch)}&productSearch=${encodeURIComponent(productSearch)}&orderStatus=${encodeURIComponent(orderStatus)}`;
    fetch(url)
      .then((response) => response.json())
      .then((result) => {
        // 解構賦值 Destructuring Assignment
        const { data, totalPages } = result;
        const tableBody = document.querySelector(".orderTableBody");
        // 換頁後，清空原本 tbody 的內容
        tableBody.innerHTML = "";
        // // 將頁碼保持在有效的區間裡
        // page = Math.max(1, Math.min(page, totalPages));

        // 將新分頁的資料取出來
        data.forEach((order) => {
          const row = `
            <tr>
              <td>${order.order_id}</td>
              <td>${order.order_date}</td>
              <td>${order.member_name}</td>
              <td>${order.payment_method}</td>
              <td>${order.full_address}</td>
              <td>${order.recipient_name}</td>
              <td>${order.total_amount}</td>
              <td>${order.order_status_name}</td>
              <td>
                <a onclick="deleteOrder(${order.order_id})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                <a href="order-edit.php?id=${order.order_id}" class="btn btn-primary ms-4"><i class="fa-solid fa-pen-to-square"></i></a>
                <button onclick="toggleDetails(${order.order_id})" class="btn btn-link ms-2"><i class="fa-solid fa-caret-down"></i></button>
              </td>
            </tr>`;
          const detailsRow = `
            <tr id="details_${order.order_id}" class="order-details" style="display: none;">
              <td colspan="9">${buildDetailsTable(order.details)}</td>
            </tr>`;
          tableBody.innerHTML += row + detailsRow;
      });
      
        
        updatePagination(page, totalPages);
        changeUrl(totalPages);
      })
      .catch((error) => console.error("Error loading orders:", error));
  }


  function changeUrl(totalPages) {
    // 從 URL 中獲取 page 參數或預設為 1
    const params = new URLSearchParams(window.location.search);
    let currentPageParam = parseInt(params.get("page") || 1);

    // 確保 page 參數不會小於 1 或大於 totalPages
    currentPageParam = Math.max(1, Math.min(currentPageParam, totalPages));

    // 更新 URL 中的 page 參數
    params.set("page", currentPageParam);

    // 將更新後的 URL 參數重新賦值給當前頁面（不重新加載頁面）
    window.history.replaceState({}, '', `${location.pathname}?${params}`);
  }


  function updatePagination(currentPage, totalPages) {
    const pagination = document.querySelector("#pagination");
    pagination.innerHTML = ""; // 清空之前的分頁鏈接

    // 計算當前組的起始和結束頁碼
    let groupStart = Math.floor((currentPage - 1) / 10) * 10 + 1;
    let groupEnd = groupStart + 9;
    if (groupEnd > totalPages) {
      groupEnd = totalPages;
    }

    // 上10頁按鈕
    const prevGroupPage = Math.max(1, groupStart - 10);
    const backTenPage = createPageItem(
      prevGroupPage,
      "<<",
      currentPage > 10 && prevGroupPage > 0
    );
    pagination.appendChild(backTenPage);

    // 上一頁按鈕
    const previousPage = Math.max(1, currentPage - 1);
    const prevPageItem = createPageItem(previousPage, "<", currentPage > 1);
    pagination.appendChild(prevPageItem);

    // 頁碼按鈕
    for (let page = groupStart; page <= groupEnd; page++) {
      const isCurrent = page === currentPage;
      const pageItem = createPageItem(page, page, page !== currentPage, isCurrent);
      pagination.appendChild(pageItem);
    }


    // 下一頁按鈕
    const nextPage = Math.min(totalPages, currentPage + 1);
    const nextPageItem = createPageItem(
      nextPage,
      ">",
      currentPage < totalPages
    );
    pagination.appendChild(nextPageItem);

    // 下10頁按鈕
    const nextGroupPage = Math.min(totalPages, groupStart + 10);
    const forwardTenPage = createPageItem(
      nextGroupPage,
      ">>",
      groupStart + 10 <= totalPages && nextGroupPage <= totalPages
    );
    pagination.appendChild(forwardTenPage);
  }

  function createPageItem(page, text, isClickable, isCurrent) {
    const pageItem = document.createElement("li");
    // 確保當頁碼是當前頁時不添加 disabled 類
    pageItem.className = "page-item" + (isCurrent ? " active" : "") + (!isClickable && !isCurrent ? " disabled" : "");
    const pageLink = document.createElement("a");
    pageLink.className = "page-link";
    // 如果按鈕是 disabled，則不應該有實際的跳轉鏈接
    pageLink.href = (isClickable || isCurrent) ? `?page=${page}` : "#";
    pageLink.textContent = text;
    pageItem.appendChild(pageLink);
    return pageItem;
  }


  showToastFromStorage();
});

const deleteOrder = (orderId) => {
  if (confirm(`是否要刪除訂單 ${orderId}？`)) {
    fetch(`api/order-delete-api.php?orderId=${orderId}`)
      .then((response) => response.json())
      .then((result) => {
        if (result.success) {
          // alert('刪除成功');
          saveToastMessage("刪除成功");
          window.location.reload(); // 刷新當前頁面或重新載入數據
        } else {
          alert("刪除失敗");
        }
      })
      .catch((error) => {
        console.error("刪除失敗:", error);
        alert("刪除錯誤，請稍後再試");
      });
  }
};

// 新增 toast
function showToast(message, isError = false) {
  const toastElement = document.getElementById("deleteToast");
  const toastBody = toastElement.querySelector(".toast-body");
  // Toast 內容
  toastBody.textContent = message;
  // Toast class
  toastBody.className = `toast-body ${
    isError ? "text-danger" : "text-success"
  }`;
  // 初始化 Toast、自動隱藏 toast
  const toast = new bootstrap.Toast(toastElement, {
    delay: 8000,
    autohide: true,
  });
  // 顯示 toast
  toast.show();
}

// 儲存 Toast 到 sessionStorage
function saveToastMessage(message, isError = false) {
  sessionStorage.setItem("toastMessage", JSON.stringify({ message, isError }));
}

// 顯示 Toast
function showToastFromStorage() {
  const toastData = sessionStorage.getItem("toastMessage");
  if (toastData) {
    const { message, isError } = JSON.parse(toastData);
    showToast(message, isError);
    sessionStorage.removeItem("toastMessage");
  }
}


function toggleDetails(orderId) {
  const detailsRow = document.getElementById('details_' + orderId);
  detailsRow.style.display = detailsRow.style.display === 'none' ? '' : 'none';
}

function buildDetailsTable(details) {
  let tableContent = `
    <table class="table table-sm">
      <thead>
        <tr>
          <th>商品編號</th>
          <th>商品名稱</th>
          <th>商品單價</th>
          <th>數量</th>
          <th>商品總金額</th>
        </tr>
      </thead>
    <tbody>`;
  details.forEach(detail => {
      const totalPrice = detail.order_unit_price * detail.order_quantity;
      tableContent += `<tr>
          <td>${detail.order_product_id}</td>
          <td>${detail.product_name}</td>
          <td>${(parseInt(detail.order_unit_price))}</td>
          <td>${detail.order_quantity}</td>
          <td>${(parseInt(totalPrice))}</td>
      </tr>`;
  });
  tableContent += '</tbody></table>';
  return tableContent;
}