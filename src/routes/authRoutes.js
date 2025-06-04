import express from 'express'
import bcrypt from 'bcryptjs'
import jwt from 'jsonwebtoken'

const router = express.Router()

router.get('/Register', (req, res) => {
    res.send('Helloo');
})




export default router