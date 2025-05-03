import express from 'express';
import {PORT} from './config/env.js';

const app = express();

app.get('/', (req,res) =>{
    res.send('Welcome to the BudgetWise API!');
});

app.listen(PORT, () => {
    console.log(`BudgetWise API running on http://localhost:${PORT}`);
});

export default app;