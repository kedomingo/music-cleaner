FROM alpine:latest
# Install SQLite
RUN apk --no-cache add sqlite
# Create a directory to store the database
WORKDIR /data
# Copy your SQLite database file into the container
COPY db /data/
# Expose the port if needed
# EXPOSE 1433

RUN sqlite3 /data/cleaner.sqlite
