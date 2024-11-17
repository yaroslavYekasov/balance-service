function displayResult(resultId, message) {
    document.getElementById(resultId).innerHTML = `<p>${message}</p>`;
}

function getBalance() {
    const userId = document.getElementById('get_user_id').value;

    fetch(`../api/get_balance.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            const result = data.balance !== undefined
                ? `Balance: €${(data.balance / 100).toFixed(2)}`
                : `Error: ${data.error}`;
            displayResult('get-balance-result', result);
        })
        .catch(error => displayResult('get-balance-result', `Error: ${error}`));
}

function addFunds() {
    const userId = document.getElementById('add_user_id').value;
    const amount = document.getElementById('add_amount').value * 100;

    fetch('../api/add_funds.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `user_id=${userId}&amount=${amount}`
    })
        .then(response => response.json())
        .then(data => {
            const result = data.success
                ? `Funds added successfully.`
                : `Error: ${data.error}`;
            displayResult('add-funds-result', result);
        })
        .catch(error => displayResult('add-funds-result', `Error: ${error}`));
}

function subtractFunds() {
    const userId = document.getElementById('subtract_user_id').value;
    const amount = document.getElementById('subtract_amount').value * 100;

    fetch('../api/subtract_funds.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `user_id=${userId}&amount=${amount}`
    })
        .then(response => response.json())
        .then(data => {
            const result = data.success
                ? `Funds subtracted successfully.`
                : `Error: ${data.error}`;
            displayResult('subtract-funds-result', result);
        })
        .catch(error => displayResult('subtract-funds-result', `Error: ${error}`));
}

function transferFunds() {
    const fromUserId = document.getElementById('from_user_id').value;
    const toUserId = document.getElementById('to_user_id').value;
    const amount = document.getElementById('transfer_amount').value * 100;

    fetch('../api/transfer_funds.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `from_user=${fromUserId}&to_user=${toUserId}&amount=${amount}`
    })
        .then(response => response.json())
        .then(data => {
            const result = data.success
                ? `Funds transferred successfully.`
                : `Error: ${data.error}`;
            displayResult('transfer-funds-result', result);
        })
        .catch(error => displayResult('transfer-funds-result', `Error: ${error}`));
}
