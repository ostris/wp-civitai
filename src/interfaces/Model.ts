import ModelVersion from './ModelVersion';
import Stats from './Stats';
import Creator from './Creator';

export default interface Model {
  id: number;
  name: string;
  description: string;
  type: string;
  poi: boolean;
  nsfw: boolean;
  allowNoCredit: boolean;
  allowCommercialUse: string;
  allowDerivatives: boolean;
  allowDifferentLicense: boolean;
  stats: Stats;
  creator: Creator;
  tags: string[];
  modelVersions: ModelVersion[];
}
