import express from 'express'
import bcrypt from 'bcryptjs'
import jwt from 'jsonwebtoken'
//import db from '../db.js'

const router = express.Router()

router.post('/register', (req, res) => {
    const body = req.body

    const hashedPassword = bcrypt.hashSync(body.password, 8);
    const hashedConfirmPassword = bcrypt.hashSync(body.confirm_password, 8);

    //console.log(hashedPassword);

    res.sendStatus(201);
    console.log(body);
})


router.post('/login', (req, res) => {
    
})






export default router