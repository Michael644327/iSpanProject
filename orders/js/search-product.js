let orderItemNum = 1; // 訂單商品項目編號
let addedProductIds = new Set(); // 儲存新增的商品

// 商品搜尋功能
document.addEventListener("DOMContentLoaded", function () {
  const orderItemContainer = document.querySelector(".order-item-container"); // 商品清單 container
  let productResults =
    document.querySelector(".search-product").nextElementSibling; // 商品選單
  let productResultsHelp = productResults.nextElementSibling; // 商品輸入欄位的 help text
  let debounceTimer;
  // let addedProductIds = new Set(); // 儲存新增的商品

  // 刪除商品
  orderItemContainer.addEventListener("click", function (event) {
    // 定義被點擊的 element （x icon）的父層，且 class 有 .delete-product
    const deleteButton = event.target.closest(".delete-product");
    // 如果確定是點擊到 deleteButton，則 .delete-product 的父層，且 class 有 .order-item

    if (deleteButton) {
      const orderItem = deleteButton.closest('.order-item');
      // 從 orderItem 中獲取商品 ID，並轉為正整數
      const productId = parseInt(orderItem.querySelector('[name="productIds[]"]').value);
      // 刪除商品元素
      orderItem.remove();
      // 從集合中移除商品 ID
      addedProductIds.delete(productId);
      // 更新訂單總金額
      updateOrderTotal();
  }
  });

  // 更新商品總金額
  orderItemContainer.addEventListener("change", function (event) {
    if (event.target.name === "productQuantities[]") {
      // 取得商品的單價與訂購數量
      const quantity = parseInt(event.target.value, 10);
      const unitPrice = parseInt(
        event.target
          .closest(".order-item")
          .querySelector('[name="productUnitPrices[]"]').value,
        10
      );
      const totalPrice = quantity * unitPrice;
      // 更新商品總金額
      event.target
        .closest(".order-item")
        .querySelector(".product-total-price").textContent = totalPrice;
      // 更新訂單總金額
      updateOrderTotal();
    }
  });

  // 定義搜尋商品欄位變數名稱
  const searchProductInput = document.querySelector(".search-product");

  // 監聽商品編號輸入
  searchProductInput.addEventListener("input", function () {
    // 初始化定時器、商品編號 help text
    clearTimeout(debounceTimer);
    productResultsHelp.innerText = "";

    // 如果輸入框無內容，隱藏選單
    if (this.value.length < 1) {
      productResults.innerHTML = "";
      productResults.classList.remove("show");
      productResultsHelp.innerText = "查無商品資料";
      return;
    }

    debounceTimer = setTimeout(() => {
      fetch(
        "api/search-product-api.php?query=" + encodeURIComponent(this.value)
      )
        .then((response) => response.json())
        .then((data) => {
          productResults.innerHTML = "";
          let productResultsAllDisabled = true; // 是否所有搜尋結果都是 order_status
          if (data.length > 0) {
            data.forEach((item) => {
              let option = document.createElement("a");

              // 如果商品狀態為啟用 1，建立商品選項
              if (item.product_status === 1) {
                option.className = "dropdown-item";
                option.href = "#";
                const displayName = item.product_name
                  ? `(${item.id}) ${item.product_name}`
                  : `(${item.id}) 無紀錄商品名稱`;
                option.textContent = displayName;
                productResultsAllDisabled = false; // 有搜到至少一個啟用的商品
                productResults.classList.add("show");
                productResults.appendChild(option);

                // 點擊後新增商品卡片
                option.addEventListener("click", function () {
                  // 如果已存在此商品，不能新增
                  if (addedProductIds.has(item.id)) {
                    productResultsHelp.innerText = `(${item.id}) ${item.product_name}已存在於訂單中`;
                  } else {
                    addedProductIds.add(item.id);

                    const productCardHtml = `
                      <div class="col-12 position-relative order-item mb-4">
                        <h6 class="mb-3">(${item.id}) ${item.product_name}</h6>
                        <button type="button" class="delete-item delete-product"><i class="fa-solid fa-xmark"></i></button>
                        <div class="col-4 mb-3">
                          <input type="number" class="form-control" id="productQuantity${orderItemNum}" name="productQuantities[]" value="1" placeholder="商品數量">
                        </div>
                        <span>剩餘庫存：${item.stock_quantity}</span>
                        <p class="mb-0">商品單價：${item.unit_price}</p>
                        <p class="mb-0">商品總金額：<span class="product-total-price">${item.unit_price}</span></p>
                        <input type="text" class="d-none" id="productId${orderItemNum}" name="productIds[]" value="${item.id}">
                        <input type="number" class="d-none" id="productUnitPrice${orderItemNum}" name="productUnitPrices[]" value="${item.unit_price}">
                      </div>          
                    `;
                    // 將商品卡片添加到 order-item-container 的最下方
                    orderItemContainer.insertAdjacentHTML("beforeend",productCardHtml);
                    // 更新訂單總金額
                    updateOrderTotal();
                  }
                  // 隱藏選單
                  productResults.classList.remove("show");
                  // 清空搜尋欄位
                  searchProductInput.value = "";

                  orderItemNum++;
                  return false;
                });
              }
            });
            if (productResultsAllDisabled) {
              console.log("有停用商品");
              productResultsHelp.innerText = "查無商品資料";
            }
          } else {
            // 如果搜尋結果為空，隱藏選單、顯示搜尋結果提示
            productResults.classList.remove("show");
            productResultsHelp.innerText = "查無商品資料";
          }
        })
        .catch((error) => {
          console.error("Error:", error);
        });
    }, 400);
    // fetch 結束
  });
  // 監聽商品編號輸入結束
});

// 更新訂單總金額
function updateOrderTotal() {
  let orderTotal = 0;
  document.querySelectorAll(".order-item").forEach((item) => {
    const quantity =
      parseInt(item.querySelector('[name="productQuantities[]"]').value, 10) ||
      0;
    const unitPrice = parseInt(
      item.querySelector('[name="productUnitPrices[]"]').value,
      10
    );
    const totalPrice = quantity * unitPrice;
    orderTotal += totalPrice;
  });
  document.querySelector(".orderTotal").textContent = orderTotal;
}
