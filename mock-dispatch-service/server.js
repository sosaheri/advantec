const express = require('express');
const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

app.post('/api/v1/dispatch', (req, requireResponse) => {
    const { order_id, amount } = req.body;

    if (!order_id || !amount) {
        return requireResponse.status(400).json({
            status: "error",
            message: "Missing required fields: order_id and amount"
        });
    }

    const crypto = require('crypto');
    const dispatchCode = `DSP-${crypto.randomBytes(4).toString('hex').toUpperCase()}`;

    return requireResponse.status(200).json({
        status: "success",
        dispatch_id: dispatchCode,
        estimated_delivery: "24-48h",
        processed_at: new Date().toISOString()
    });
});

app.listen(PORT, () => {
    console.log(`Mock Dispatch Service running on port ${PORT}`);
});