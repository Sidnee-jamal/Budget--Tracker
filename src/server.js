import express from 'express';
import path, {dirname} from 'path';
import { fileURLToPath } from 'url';
import authRoutes from './routes/authRoutes.js';

const app = express()
const port = process.env.PORT || 3000;


const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);


app.use(express.json())
app.use(express.urlencoded({extended: true}));

app.use(express.static(path.join(__dirname, '../Forms')))
app.use(express.static(path.join(__dirname, '../public')))
app.use('/css', express.static(path.join(__dirname, '../css')))


app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname,'public','index.html'))
});

app.get('/add-goal', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','add-goal.html'))
});

app.get('/add-wallet', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','add-wallet.html'))
});

app.get('/addoreditcategory', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','addoreditcategory.html'))
});

app.get('/addtransaction', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','addtransaction.html'))
});

app.get('/admin-login', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','admin-login.html'))
});

app.get('/edit-goal', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','edit-goal.html'))
});

app.get('/edittransaction', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','edittransaction.html'))
});

app.get('/login', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','login.html'))
});

app.get('/register', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','register.html'))
});

app.get('/set-budget', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','set-budget.html'))
});

app.get('/updateprofile', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','updateprofile.html'))
});

app.get('/user-management', (req, res) => {
    res.sendFile(path.join(__dirname,'../Forms','user-management.html'))
});


//Routes
app.use('/auth', authRoutes)




app.listen(port, () => {
    console.log(`Server is running on port: ${port}`);
});