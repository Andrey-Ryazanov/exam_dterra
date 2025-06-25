document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('currency-date');
    const tableContainer = document.getElementById('currency-table');
    const initialTableHTML = tableContainer.innerHTML;

    dateInput.addEventListener('change', function () {
        const date = this.value;
        const dateFormatted = date.split('-').reverse().join('.');

        // Показываем индикатор загрузки
        tableContainer.innerHTML = `
            <div class="loading">
                ${window.CurrencyLang.LOADING}
            </div>
        `;

        fetch('/local/components/dterra/currency/ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Bitrix-Csrf-Token': BX.bitrix_sessid()
            },
            body: 'date=' + encodeURIComponent(dateFormatted)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) 
            {
                // Обработка ошибки
                tableContainer.innerHTML = `
                    <div class="currency-exchange__error">
                        <div class="alert alert-danger">
                            ${data.message}
                        </div>
                    </div>
                `;
                return;
            }

            // Создаем контейнер для таблицы
            const tableContent = document.createElement('div');
            
            // Добавляем заголовок таблицы
            tableContent.innerHTML = `
                <div class="currency-exchange__line">
                    <div class="ui-p4">
                        ${window.CurrencyLang.CURRENCY}
                    </div>
                    <div class="ui-p4">
                        ${window.CurrencyLang.EXCHANGE_RATE}
                    </div>
                </div>
            `;

            if (data.rates && Object.keys(data.rates).length > 0) 
            {
                // Добавляем строки с валютами
                Object.entries(data.rates).forEach(([code, value]) => {
                    tableContent.innerHTML += `
                        <div class="currency-exchange__line">
                            <div class="ui-p1">
                                ${code}
                                <span>/RUB</span>
                            </div>
                            <div class="ui-p1">
                                ${value}
                            </div>
                        </div>
                    `;
                });
            } 
            else 
            {
                // Добавляем сообщение об отсутствии данных
                tableContent.innerHTML += `
                    <div class="currency-exchange__message">
                        <div class="ui-p1">
                            ${window.CurrencyLang.DATA_NOT_FOUND}
                        </div>
                    </div>
                `;
            }

            // Очищаем и добавляем новый контент
            tableContainer.innerHTML = '';
            tableContainer.appendChild(tableContent);
        })
        .catch(error => {
            console.error(`${window.CurrencyLang.DATA_ERROR}:`, error);
            tableContainer.innerHTML = initialTableHTML;
        });
    });
});