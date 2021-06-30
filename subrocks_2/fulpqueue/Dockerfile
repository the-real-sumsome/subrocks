#### FFMPEG + PYTHON 3.9

FROM python:3.9-alpine
RUN apk add ffmpeg gcc musl-dev linux-headers

### FULPQUEUE SHIT

WORKDIR /usr/src/fulpqueue
COPY requirements.txt .
RUN pip3 install -r requirements.txt

COPY . .
CMD ["python3", "queue.py"]