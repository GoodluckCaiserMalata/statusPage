import express from 'express';
import cors from 'cors';
import morgan from 'morgan';
import helmet from 'helmet';
import http from 'http';

const app = express();
const PORT = 3000;

app.use(cors());
app.use(express.text());
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

app.use(morgan('dev'));
app.use(helmet());

const server = http.createServer(app);

app.use('/dead-routes', (req, res) => {
    res.json({message: 'accepted'});
    const emgDeadRoutes = req.body;
    reportIssues(emgDeadRoutes);
});

server.listen(PORT, () => {
    console.info(`App running on port ${PORT}`);
});
