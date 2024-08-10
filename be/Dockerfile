# Sử dụng Node.js chính thức làm hình ảnh cơ sở
FROM node:16

# Tạo thư mục làm việc
WORKDIR /usr/src/app

# Sao chép package.json và package-lock.json vào thư mục làm việc
COPY ./be/package*.json ./

# Cài đặt các phụ thuộc
RUN npm install

# Sao chép mã nguồn ứng dụng vào thư mục làm việc
COPY . .

# Mở cổng mà ứng dụng sẽ chạy
EXPOSE 3000

# Chạy ứng dụng khi container khởi động
CMD [ "node", "./be/index.js" ]
