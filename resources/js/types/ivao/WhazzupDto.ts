export type WhazzupDto = {
	updatedAt: string;
	clients: ClientsDto;
	servers: ServerDto[];
	voiceServers: ServerDto[];
	connections: WhazzupConnectionsDto;
};

export type ClientsDto = {
	pilots: BaseSessionDto[];
	atcs: BaseSessionDto[];
	observers: BaseSessionDto[];
	followMe: BaseSessionDto[];
};

export type BaseSessionDto = {
	id: number;
	callsign: string;
	userId: number;
	connectionType: string;
	serverId: string;
	time: number;
	softwareTypeId: string;
	softwareVersion: string;
	sandbagging: boolean;
	isMilitary: boolean;
	isWorldTour: boolean;
	lastTrack: LastTrackDto;
	flightPlans: string[];
	softwareType: SoftwareSummaryDto;
	user: UserSummaryDto;
	createdAt: string;
	completedAt?: string;
	updatedAt: string;
};

export type SoftwareSummaryDto = {
	id: number;
	name: string;
};

export type UserSummaryDto = {
	id: number;
	firstName: string;
	lastName: string;
	divisionId: string;
	rating: UserRatingDto;
};

export type UserRatingDto = {
	isAtc: boolean;
	isPilot: boolean;
	pilotRating: BaseRatingDto;
	atcRating: BaseRatingDto;
	networkRating: NetworkRatingDto;
};

export type BaseRatingDto = {
	id: number;
	name: string;
	shortName: string;
	description: string;
};

export type NetworkRatingDto = {
	id: number;
	name: string;
	description: string;
};

export type ServerDto = {
	id: number;
	hostname: string;
	ip: string;
	description: string;
	countryId: string;
	currentConnections: number;
	maximumConnections: number;
};

export type WhazzupConnectionsDto = {
	total: number;
	supervisor: number;
	atc: number;
	observer: number;
	pilot: number;
	worldTour: number;
	followMe: number;
	uniqueUsers24h: number;
};

export type LastTrackDto = {
	time: number;
	numbertimestamp: string; // ISO 8601 string ($date-time)
	latitude: number;
	longitude: number;
	altitude: number;
	altitudeDifference: number;
	arrivalDistance: number;
	departureDistance: number;
	groundSpeed: number;
	heading: number;
	onGround: boolean;
	state: string;
	transponder: number;
	transponderMode: string;
};
