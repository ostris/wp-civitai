export default interface FileInfo {
  name: string;
  id: number;
  sizeKB: number;
  type: string;
  metadata: {
    fp: string;
    size: string;
    format: string;
  };
  pickleScanResult: string;
  pickleScanMessage: string;
  virusScanResult: string;
  scannedAt: string;
  hashes: {
    AutoV1: string;
    AutoV2: string;
    SHA256: string;
    CRC32: string;
    BLAKE3: string;
  };
  downloadUrl: string;
  primary: boolean;
}
