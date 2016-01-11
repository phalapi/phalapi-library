//
//  MyDES.h
//  ZGRDemo
//
//  Created by zhou on 7/6/15.
//  Copyright (c) 2015 zhou. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface MyDES : NSObject{
    NSString *baseUrl;
    NSString *basekey;
}

@property (nonatomic, copy) void (^successCompletionBlock)(MyDES *);
@property (nonatomic, copy) void (^failureCompletionBlock)(MyDES *);

+(instancetype)shareManager;
+(instancetype)initManager;

-(NSString*) decryptUseDES:(NSString*)cipherText key:(NSString*)key;   //解密
-(NSString *) encryptUseDES:(NSString *)clearText key:(NSString*)key;  //加密

- (void)startWithCompletionBlockWithSuccess:(void (^)(MyDES *myDes))success
                                    failure:(void (^)(MyDES *myDes))failure;
@end
